<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpCodeMail;
use App\Models\LoginLog;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->merge([
            'phone_number' => $request->input('phone_number')
                ?? $request->input('no_telp')
                ?? $request->input('phone'),
        ]);

        $validated = $request->validate([
            'phone_number' => 'required|string|max:30',
            'email' => 'required|email|max:150',
        ]);

        $phone = $validated['phone_number'];
        $email = $validated['email'];

        $user = User::firstOrCreate(
            ['phone_number' => $phone],
            [
                'name' => 'Pasien '.substr($phone, -4),
                'email' => $email,
                'role' => 'patient',
                'is_active' => true,
            ]
        );
        if ($user->email !== $email) {
            $user->update(['email' => $email]);
        }

        $otp = (string) random_int(100000, 999999);
        OtpCode::where('identifier', $phone)
            ->where('is_used', false)
            ->update(['is_used' => true]);
        OtpCode::create([
            'identifier' => $phone,
            'otp_code' => $otp,
            'type' => 'REGISTER',
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
        ]);

        Mail::to($email)->send(new OtpCodeMail($otp, $phone));

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP telah berhasil dikirimkan ke email Anda.',
            'data' => [
                'identifier' => $phone,
                'otp_sent_to' => $email,
            ],
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string',
        ]);

        $identifier = $validated['identifier'];
        $otp = $validated['otp'];

        $record = OtpCode::where('identifier', $identifier)
            ->where('otp_code', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest('created_at')
            ->first();

        if (! $record) {
            return response()->json([
                'status' => 'error',
                'error' => 'INVALID_OTP',
                'message' => 'Kode OTP yang dimasukkan tidak valid atau sudah kedaluwarsa.',
            ], 400);
        }

        $record->update(['is_used' => true]);

        $user = User::where('phone_number', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Pasien '.substr($identifier, -4),
                'phone_number' => $identifier,
                'email' => $identifier.'@rsud.pasien.local',
                'role' => 'patient',
                'is_active' => true,
                'phone_verified_at' => now(),
            ]);
        } else {
            $user->update(['phone_verified_at' => now()]);
        }

        // Record audit trail
        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'user_agent' => $request->userAgent() ?? 'Flutter Mobile App',
            'device_type' => $request->header('X-Device-Type', 'MOBILE_APP'),
            'login_method' => 'OTP',
            'status' => 'SUCCESS',
        ]);

        $token = $user->createToken('mobile_patient_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi OTP berhasil.',
            'data' => [
                'token' => $token,
                'has_pin' => ! empty($user->pin_hash),
                'has_registered_patient' => (bool) $user->has_registered_patient,
                'no_rm' => $user->no_rm,
                'nama_pasien' => $user->nama_pasien,
                'user' => $user,
            ],
        ]);
    }

    public function setPin(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:6',
            'identifier' => 'nullable|string',
            'phone' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        $pin = $validated['pin'];
        $user = auth('sanctum')->user() ?? $request->user();

        if (! $user) {
            $identifier = trim(
                $validated['identifier']
                ?? $validated['phone']
                ?? $validated['phone_number']
                ?? ''
            );

            if (! empty($identifier)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
                $candidates = [$identifier];
                if (str_starts_with($cleanPhone, '62')) {
                    $candidates[] = '0' . substr($cleanPhone, 2);
                    $candidates[] = substr($cleanPhone, 2);
                } elseif (str_starts_with($cleanPhone, '0')) {
                    $candidates[] = substr($cleanPhone, 1);
                    $candidates[] = '62' . substr($cleanPhone, 1);
                } else {
                    $candidates[] = '0' . $cleanPhone;
                    $candidates[] = '62' . $cleanPhone;
                }

                $user = User::whereIn('phone_number', $candidates)
                    ->orWhere('email', $identifier)
                    ->first();
            }
        }

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'error' => 'UNAUTHENTICATED',
                'message' => 'Sesi autentikasi tidak ditemukan. Silakan masukkan nomor handphone Anda.',
            ], 401);
        }

        $user->update([
            'pin_hash' => Hash::make($pin),
        ]);

        $token = $user->createToken('mobile_patient_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'PIN keamanan berhasil disimpan.',
            'data' => [
                'token' => $token,
                'has_pin' => true,
                'has_registered_patient' => (bool) $user->has_registered_patient,
                'no_rm' => $user->no_rm,
                'nama_pasien' => $user->nama_pasien,
                'user' => $user,
            ],
        ]);
    }

    public function loginPin(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'pin' => 'required|string|size:6',
        ]);

        $identifier = trim($validated['identifier']);
        $pin = $validated['pin'];

        // Normalize variations (e.g. 08123..., 8123..., 628123..., +628123...)
        $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
        $candidates = [$identifier];
        if (str_starts_with($cleanPhone, '62')) {
            $candidates[] = '0' . substr($cleanPhone, 2);
            $candidates[] = substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '0')) {
            $candidates[] = substr($cleanPhone, 1);
            $candidates[] = '62' . substr($cleanPhone, 1);
        } else {
            $candidates[] = '0' . $cleanPhone;
            $candidates[] = '62' . $cleanPhone;
        }

        $user = User::whereIn('phone_number', $candidates)
            ->orWhere('email', $identifier)
            ->first();

        if (! $user || empty($user->pin_hash) || ! Hash::check($pin, $user->pin_hash)) {
            if ($user) {
                LoginLog::create([
                    'user_id' => $user->id,
                    'ip_address' => $request->ip() ?? '127.0.0.1',
                    'user_agent' => $request->userAgent() ?? 'Flutter Mobile App',
                    'device_type' => $request->header('X-Device-Type', 'MOBILE_APP'),
                    'login_method' => 'PIN',
                    'status' => 'FAILED',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'error' => 'INVALID_PIN',
                'message' => empty($user?->pin_hash) 
                    ? 'Akun ini belum memiliki PIN. Silakan masuk dengan kode OTP terlebih dahulu.'
                    : 'Nomor handphone atau PIN yang dimasukkan salah.',
            ], 401);
        }

        // Record audit trail
        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'user_agent' => $request->userAgent() ?? 'Flutter Mobile App',
            'device_type' => $request->header('X-Device-Type', 'MOBILE_APP'),
            'login_method' => 'PIN',
            'status' => 'SUCCESS',
        ]);

        $token = $user->createToken('mobile_patient_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'has_pin' => true,
                'has_registered_patient' => (bool) $user->has_registered_patient,
                'no_rm' => $user->no_rm,
                'nama_pasien' => $user->nama_pasien,
                'user' => $user,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'email' => $user->email,
                'role' => $user->role,
                'has_registered_patient' => (bool) $user->has_registered_patient,
                'no_rm' => $user->no_rm,
                'nama_pasien' => $user->nama_pasien,
                'has_pin' => ! empty($user->pin_hash),
            ],
        ]);
    }

    public function updatePatientProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:150',
            'nama_pasien' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150',
            'no_rm' => 'nullable|string|max:50',
        ]);

        $user = $request->user();

        $updateData = [];
        if (! empty($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }
        if (! empty($validated['nama_pasien'])) {
            $updateData['nama_pasien'] = $validated['nama_pasien'];
            // If name is not provided, also sync name
            if (empty($validated['name'])) {
                $updateData['name'] = $validated['nama_pasien'];
            }
        }
        if (! empty($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }
        if (! empty($validated['no_rm'])) {
            $updateData['no_rm'] = $validated['no_rm'];
            $updateData['has_registered_patient'] = true;
        }

        if (! empty($updateData)) {
            $user->update($updateData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profil pasien berhasil diperbarui.',
            'data' => [
                'user' => $user->fresh(),
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi login telah berhasil diakhiri.',
        ]);
    }
}

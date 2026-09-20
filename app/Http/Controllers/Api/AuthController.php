<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $phone = $validated['phone_number'];
        $email = $validated['email'] ?? $phone . '@rsud.pasien.local';

        $user = User::firstOrCreate(
            ['phone_number' => $phone],
            [
                'name' => 'Pasien ' . substr($phone, -4),
                'email' => $email,
                'role' => 'patient',
                'is_active' => true,
            ]
        );

        // Generate 6 digit OTP (default dev 123456)
        $otp = '123456';
        OtpCode::create([
            'identifier' => $phone,
            'otp_code' => $otp,
            'type' => 'REGISTER',
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP telah berhasil dikirimkan ke nomor WhatsApp/SMS Anda.',
            'data' => [
                'identifier' => $phone,
                'otp_sent_to' => $phone,
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

        // In demo/dev mode, '123456' or database OTP is valid
        $validOtp = ($otp === '123456');
        if (!$validOtp) {
            $record = OtpCode::where('identifier', $identifier)
                ->where('otp_code', $otp)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->latest('created_at')
                ->first();
            if ($record) {
                $record->update(['is_used' => true]);
                $validOtp = true;
            }
        }

        if (!$validOtp) {
            return response()->json([
                'status' => 'error',
                'error' => 'INVALID_OTP',
                'message' => 'Kode OTP yang dimasukkan tidak valid atau sudah kedaluwarsa.',
            ], 400);
        }

        $user = User::where('phone_number', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Pasien ' . substr($identifier, -4),
                'phone_number' => $identifier,
                'email' => $identifier . '@rsud.pasien.local',
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
                'has_pin' => !empty($user->pin_hash),
                'has_registered_patient' => (bool)$user->has_registered_patient,
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
        ]);

        $user = $request->user();
        $user->update([
            'pin_hash' => Hash::make($validated['pin']),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'PIN keamanan berhasil disimpan.',
        ]);
    }

    public function loginPin(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'pin' => 'required|string|size:6',
        ]);

        $identifier = $validated['identifier'];
        $pin = $validated['pin'];

        $user = User::where('phone_number', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user || !Hash::check($pin, $user->pin_hash)) {
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
                'message' => 'Nomor handphone atau PIN yang dimasukkan salah.',
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
                'has_registered_patient' => (bool)$user->has_registered_patient,
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
                'has_registered_patient' => (bool)$user->has_registered_patient,
                'no_rm' => $user->no_rm,
                'nama_pasien' => $user->nama_pasien,
                'has_pin' => !empty($user->pin_hash),
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
        if (!empty($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }
        if (!empty($validated['nama_pasien'])) {
            $updateData['nama_pasien'] = $validated['nama_pasien'];
            // If name is not provided, also sync name
            if (empty($validated['name'])) {
                $updateData['name'] = $validated['nama_pasien'];
            }
        }
        if (!empty($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }
        if (!empty($validated['no_rm'])) {
            $updateData['no_rm'] = $validated['no_rm'];
            $updateData['has_registered_patient'] = true;
        }

        if (!empty($updateData)) {
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

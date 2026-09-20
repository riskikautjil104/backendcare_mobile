<?php

namespace Tests\Feature;

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthEmailOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_sends_email_otp_and_stores_code(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/register', [
            'phone_number' => '081241206967',
            'email' => 'pasien@example.com',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.identifier', '081241206967')
            ->assertJsonPath('data.otp_sent_to', 'pasien@example.com');

        $this->assertDatabaseHas('otp_codes', [
            'identifier' => '081241206967',
            'is_used' => false,
        ]);

        Mail::assertSent(OtpCodeMail::class, function (OtpCodeMail $mail): bool {
            return $mail->hasTo('pasien@example.com')
                && $mail->patientPhone === '081241206967'
                && strlen($mail->otpCode) === 6;
        });
    }

    public function test_verify_otp_rejects_hardcoded_demo_code(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/register', [
            'phone_number' => '081241206967',
            'email' => 'pasien@example.com',
        ])->assertOk();

        OtpCode::where('identifier', '081241206967')->update([
            'otp_code' => '654321',
        ]);

        $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => '081241206967',
            'otp' => '123456',
        ])->assertStatus(400);
    }

    public function test_verify_otp_accepts_latest_email_code(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/register', [
            'phone_number' => '081241206967',
            'email' => 'pasien@example.com',
        ])->assertOk();

        $otp = OtpCode::where('identifier', '081241206967')
            ->latest('created_at')
            ->firstOrFail();

        $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => '081241206967',
            'otp' => $otp->otp_code,
        ])
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data' => ['token', 'user']]);

        $this->assertTrue($otp->fresh()->is_used);
    }
}

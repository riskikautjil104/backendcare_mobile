<?php

namespace Tests\Feature;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardOtpPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_dashboard_displays_recent_otp_codes(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'phone_number' => '081200000001',
            'password' => Hash::make('password'),
        ]);

        OtpCode::create([
            'identifier' => '081241206967',
            'otp_code' => '654321',
            'type' => 'REGISTER',
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
        ]);

        $this->actingAs($superadmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Kode OTP Email Terbaru')
            ->assertSee('654321')
            ->assertSee('Aktif');
    }
}

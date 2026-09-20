<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SwaggerDocController extends Controller
{
    /**
     * Tampilkan halaman Swagger UI Tester di Super Admin portal.
     */
    public function index()
    {
        return view('admin.swagger.index');
    }

    /**
     * Menghasilkan OpenAPI 3.0.0 JSON Specification lengkap (Laravel API + SIMRS Ternate).
     */
    public function openApiJson(Request $request)
    {
        $baseUrl = url('/api/v1');
        $simrsBaseUrl = env('SIMRS_BASE_URL', 'http://192.168.1.100/medifirst2000');

        $spec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'RSUD Dr. H. Chasan Boesoirie - Integrated API Tester',
                'description' => 'Dokumentasi interaktif OpenAPI 3.0 & Swagger UI untuk pengujian seluruh endpoint: Autentikasi, Profil Pasien, Banner, Tema Dinamis, Antrean Poli Live, Antrean Obat & Resep Farmasi, Bed Monitoring, dan Reservasi SIMRS Ternate.',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Tim IT RSUD Dr. H. Chasan Boesoirie',
                ],
            ],
            'servers' => [
                [
                    'url' => $baseUrl,
                    'description' => 'Laravel Backend API (Current Server)',
                ],
                [
                    'url' => $simrsBaseUrl,
                    'description' => 'SIMRS Ternate Medifirst2000 (Bridging / Proxy)',
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT / Sanctum Token',
                        'description' => 'Masukkan Bearer Token hasil login untuk mengakses endpoint terproteksi.',
                    ],
                ],
            ],
            'paths' => [
                // LARAVEL: THEME & PUBLIC
                '/app-theme' => [
                    'get' => [
                        'tags' => ['Laravel: Public & System'],
                        'summary' => 'Get Active Mobile Theme Colors',
                        'description' => 'Mengambil konfigurasi palet warna dinamis aplikasi mobile dari Super Admin.',
                        'responses' => [
                            '200' => ['description' => 'Sukses mengambil konfigurasi warna.'],
                        ],
                    ],
                ],
                '/banners' => [
                    'get' => [
                        'tags' => ['Laravel: Public & System'],
                        'summary' => 'Get Active Mobile Banners',
                        'description' => 'Mengambil daftar banner pengumuman & promosi aktif di beranda mobile.',
                        'responses' => [
                            '200' => ['description' => 'Daftar banner berhasil diambil.'],
                        ],
                    ],
                ],
                '/help-center' => [
                    'get' => [
                        'tags' => ['Laravel: Public & System'],
                        'summary' => 'Get Help Center & Hospital Contacts',
                        'description' => 'Mengambil nomor kontak darurat RSUD dan daftar FAQ.',
                        'responses' => [
                            '200' => ['description' => 'Informasi kontak dan FAQ berhasil diambil.'],
                        ],
                    ],
                ],

                // LARAVEL: AUTHENTICATION
                '/auth/register' => [
                    'post' => [
                        'tags' => ['Laravel: Authentication'],
                        'summary' => 'Register Phone & Send OTP',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['phone', 'name'],
                                        'properties' => [
                                            'phone' => ['type' => 'string', 'example' => '081234567890'],
                                            'name' => ['type' => 'string', 'example' => 'Fulan bin Fulan'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'OTP berhasil dikirim.'],
                        ],
                    ],
                ],
                '/auth/verify-otp' => [
                    'post' => [
                        'tags' => ['Laravel: Authentication'],
                        'summary' => 'Verify OTP & Get Sanctum Token',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['phone', 'otp'],
                                        'properties' => [
                                            'phone' => ['type' => 'string', 'example' => '081234567890'],
                                            'otp' => ['type' => 'string', 'example' => '123456'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Verifikasi OTP berhasil, menghasilkan token auth.'],
                        ],
                    ],
                ],
                '/auth/login-pin' => [
                    'post' => [
                        'tags' => ['Laravel: Authentication'],
                        'summary' => 'Login via 6-Digit PIN',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['phone', 'pin'],
                                        'properties' => [
                                            'phone' => ['type' => 'string', 'example' => '081234567890'],
                                            'pin' => ['type' => 'string', 'example' => '123456'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Login PIN berhasil.'],
                        ],
                    ],
                ],
                '/auth/me' => [
                    'get' => [
                        'tags' => ['Laravel: Patient Profile'],
                        'summary' => 'Get Current Authenticated User Profile',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Data profil user & status pasien.'],
                        ],
                    ],
                ],
                '/auth/set-pin' => [
                    'post' => [
                        'tags' => ['Laravel: Patient Profile'],
                        'summary' => 'Set or Update 6-Digit Security PIN',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['pin'],
                                        'properties' => [
                                            'pin' => ['type' => 'string', 'example' => '123456'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'PIN keamanan berhasil disimpan.'],
                        ],
                    ],
                ],
                '/user/patient-profile' => [
                    'post' => [
                        'tags' => ['Laravel: Patient Profile'],
                        'summary' => 'Sync Patient No RM to User Account',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['no_rm'],
                                        'properties' => [
                                            'no_rm' => ['type' => 'string', 'example' => '012345'],
                                            'nama_pasien' => ['type' => 'string', 'example' => 'Fulan bin Fulan'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Profil pasien terhubung.'],
                        ],
                    ],
                ],
                '/reservations' => [
                    'get' => [
                        'tags' => ['Laravel: Reservations Tracking'],
                        'summary' => 'List User Reservations (Backend Cache)',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Daftar riwayat reservasi tersimpan.'],
                        ],
                    ],
                    'post' => [
                        'tags' => ['Laravel: Reservations Tracking'],
                        'summary' => 'Store Reservation Record in Backend',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['no_reservasi', 'ruangan', 'dokter', 'tanggal_rencana'],
                                        'properties' => [
                                            'no_reservasi' => ['type' => 'string', 'example' => 'RES-20260921-0001'],
                                            'ruangan' => ['type' => 'string', 'example' => 'Poli Penyakit Dalam'],
                                            'dokter' => ['type' => 'string', 'example' => 'dr. Ahmad Syarif, Sp.PD'],
                                            'tanggal_rencana' => ['type' => 'string', 'format' => 'date', 'example' => '2026-09-25'],
                                            'jam_praktek' => ['type' => 'string', 'example' => '08:00 - 12:00'],
                                            'no_antrian' => ['type' => 'string', 'example' => '12'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => ['description' => 'Reservasi tersimpan di tracking backend.'],
                        ],
                    ],
                ],

                // SIMRS: FARMASI & OBAT
                '/viewer/get-list-antrian-farmasi' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Farmasi & Resep Obat'],
                        'summary' => 'Get Real-time Pharmacy Queue & Status Obat',
                        'description' => 'Mengambil daftar antrean resep obat dan progres peracikan farmasi (Diterima, Sedang Diracik, Siap Diambil di Loket).',
                        'responses' => [
                            '200' => ['description' => 'Status antrean obat berhasil diambil.'],
                        ],
                    ],
                ],

                // SIMRS: MONITORING ANTREAN POLI LIVE
                '/viewer/get-list-antrian' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Antrean Poliklinik'],
                        'summary' => 'Get Live Clinic Call Status (Display Poli)',
                        'description' => 'Mengambil nomor antrean yang sedang dipanggil oleh dokter di ruang periksa poliklinik secara live.',
                        'responses' => [
                            '200' => ['description' => 'Data panggilan antrean poli.'],
                        ],
                    ],
                ],

                // SIMRS: KETERSEDIAAN TEMPAT TIDUR (BED MONITORING)
                '/kiosk/get-view-bed' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Ketersediaan Kamar / Bed'],
                        'summary' => 'Get Real-time Inpatient Bed Availability',
                        'description' => 'Mengambil ketersediaan tempat tidur rawat inap (VVIP, VIP, Kelas 1, 2, 3, ICU) per ruangan dan nomor bed.',
                        'responses' => [
                            '200' => ['description' => 'Daftar status tempat tidur rawat inap.'],
                        ],
                    ],
                ],
                '/kiosk/get-combo' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Ketersediaan Kamar / Bed'],
                        'summary' => 'Get Combo Room & Class Options',
                        'description' => 'Daftar master ruangan dan kelas tempat tidur untuk opsi filter pencarian.',
                        'responses' => [
                            '200' => ['description' => 'Data combo kelas dan ruangan rawat inap.'],
                        ],
                    ],
                ],

                // SIMRS: JADWAL DOKTER TERPADU
                '/kiosk/get-daftar-jadwal-dokter' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Dokter & Poli'],
                        'summary' => 'Get Doctor Practice Schedule',
                        'parameters' => [
                            [
                                'name' => 'ruanganId',
                                'in' => 'query',
                                'required' => false,
                                'schema' => ['type' => 'string'],
                                'description' => 'Filter berdasarkan ID ruangan poli (opsional)',
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Jadwal dokter terpadu.'],
                        ],
                    ],
                ],

                // SIMRS: RESERVASI ONLINE & CHECK-IN
                '/reservasionline/get-list-data' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Reservasi & Check-in'],
                        'summary' => 'Get Master Poli & Dokter SIMRS',
                        'responses' => [
                            '200' => ['description' => 'Daftar poli dan dokter.'],
                        ],
                    ],
                ],
                '/reservasionline/get-slotting-by-ruangan-new/{idPoli}/{tgl}' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Reservasi & Check-in'],
                        'summary' => 'Get Doctor Quota Slotting',
                        'parameters' => [
                            ['name' => 'idPoli', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string'], 'example' => '12'],
                            ['name' => 'tgl', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string'], 'example' => '2026-09-25'],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Sisa kuota dokter pada poli dan tanggal terpilih.'],
                        ],
                    ],
                ],
                '/reservasionline/get-libur' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Reservasi & Check-in'],
                        'summary' => 'Get Hospital Holidays Calendar',
                        'responses' => [
                            '200' => ['description' => 'Daftar tanggal libur resmi rumah sakit.'],
                        ],
                    ],
                ],
                '/reservasionline/save' => [
                    'post' => [
                        'tags' => ['SIMRS Ternate: Reservasi & Check-in'],
                        'summary' => 'Book Online Reservation Ticket',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['nocm', 'namapasien', 'objectruanganfk', 'objectpegawaifk', 'tglreservasi'],
                                        'properties' => [
                                            'nocm' => ['type' => 'string', 'example' => '012345'],
                                            'namapasien' => ['type' => 'string', 'example' => 'Fulan bin Fulan'],
                                            'jeniskelamin' => ['type' => 'string', 'example' => 'L'],
                                            'tgllahir' => ['type' => 'string', 'example' => '1990-01-01'],
                                            'nohp' => ['type' => 'string', 'example' => '081234567890'],
                                            'objectruanganfk' => ['type' => 'integer', 'example' => 12],
                                            'objectpegawaifk' => ['type' => 'integer', 'example' => 44],
                                            'tglreservasi' => ['type' => 'string', 'example' => '2026-09-25'],
                                            'jamreservasi' => ['type' => 'string', 'example' => '08:00 - 12:00'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Tiket reservasi online terbit beserta nomor antrean dan norec.'],
                        ],
                    ],
                ],
                '/reservasionline/delete' => [
                    'post' => [
                        'tags' => ['SIMRS Ternate: Reservasi & Check-in'],
                        'summary' => 'Cancel Reservation by Norec',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['norec'],
                                        'properties' => [
                                            'norec' => ['type' => 'string', 'example' => 'rec_981249817293'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Reservasi berhasil dibatalkan.'],
                        ],
                    ],
                ],
                '/reservasionline/update-ischeckin-kiosk' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Reservasi & Check-in'],
                        'summary' => 'Check-in Attendance at Kiosk / Mobile APM',
                        'parameters' => [
                            ['name' => 'noReservasi', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'string'], 'example' => 'RES-20260925-0012'],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Status pasien berubah menjadi HADIR di antrean poliklinik.'],
                        ],
                    ],
                ],
                '/reservasionline/get-history' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Pasien & Rekam Medis'],
                        'summary' => 'Get Patient Medical Visit History',
                        'parameters' => [
                            ['name' => 'nocmNama', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'string'], 'example' => '012345'],
                            ['name' => 'tgllahir', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string'], 'example' => '1990-01-01'],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Riwayat kunjungan berobat pasien.'],
                        ],
                    ],
                ],
                '/reservasionline/cek-pasien-baru-by-nik/{nik}' => [
                    'get' => [
                        'tags' => ['SIMRS Ternate: Pasien & Rekam Medis'],
                        'summary' => 'Check Patient Registration by NIK',
                        'parameters' => [
                            ['name' => 'nik', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string'], 'example' => '8271010101900001'],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Hasil verifikasi NIK di database SIMRS RSUD.'],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($spec);
    }
}

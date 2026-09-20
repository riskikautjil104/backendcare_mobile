<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_booking',
        'id_poli',
        'nama_poli',
        'id_dokter',
        'nama_dokter',
        'tanggal_praktik',
        'jam_slot',
        'estimasi_kedatangan',
        'tipe_pasien',
        'jenis_penjamin',
        'total_biaya',
        'status_pembayaran',
        'status_antrean',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_praktik' => 'date',
            'total_biaya' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

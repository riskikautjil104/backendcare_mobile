<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_igd',
        'phone_ambulance',
        'wa_cs',
        'wa_pengaduan',
        'email',
        'address',
        'maps_url',
        'complaint_url',
        'survey_url',
        'website_url',
        'operational_hours',
    ];
}

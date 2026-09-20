<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use App\Models\UserReservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients = User::where('role', 'patient')->count();
        $totalReservations = UserReservation::count();
        $todayReservations = UserReservation::whereDate('tanggal_praktik', today())->count();
        $todayLogins = LoginLog::whereDate('created_at', today())->count();

        $recentLogs = LoginLog::with('user')
            ->latest('created_at')
            ->take(10)
            ->get();

        $recentReservations = UserReservation::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalReservations',
            'todayReservations',
            'todayLogins',
            'recentLogs',
            'recentReservations'
        ));
    }

    public function logs(Request $request)
    {
        $query = LoginLog::with('user')->latest('created_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhere('device_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.logs', compact('logs'));
    }

    public function reservations(Request $request)
    {
        $query = UserReservation::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                  ->orWhere('nama_poli', 'like', "%{$search}%")
                  ->orWhere('nama_dokter', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $reservations = $query->paginate(20)->withQueryString();

        return view('admin.reservations', compact('reservations'));
    }
}

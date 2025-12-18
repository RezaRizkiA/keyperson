<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Appointment;

class DashboardService
{
    protected $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Mengambil statistik ringkas untuk halaman Dashboard Admin
     */
    public function getAdminStats()
    {
        return [
            // Hitung Total User di sistem
            'total_users' => User::count(),

            // Hitung Total Appointment (Semua)
            // Menggunakan repo atau query langsung juga boleh untuk count sederhana
            'total_appointments' => Appointment::count(),

            // Hitung Total Pendapatan (Status Paid)
            'total_revenue' => Transaction::where('status', 'berhasil')->sum('amount'),

            // Hitung Appointment yang butuh konfirmasi (Pending)
            'pending_appointments' => Appointment::where('status', 'need_confirmation')->count(),
        ];
    }

    /**
     * Mengambil statistik dashboard khusus Expert
     * @param int $expertId
     */
    public function getExpertStats($expertId)
    {
        return [
            // 1. Total Sesi (Semua status)
            'total_appointments' => Appointment::where('expert_id', $expertId)->count(),

            // 2. Sesi Upcoming (Yang harus dipersiapkan)
            // Mengambil status 'confirmed' dan 'progress'
            'upcoming_appointments' => Appointment::where('expert_id', $expertId)
                ->whereIn('status', ['confirmed', 'progress'])
                ->where('date_time', '>=', now())
                ->count(),

            // 3. Menunggu Konfirmasi (Action Needed)
            'need_confirmation' => Appointment::where('expert_id', $expertId)
                ->where('status', 'need_confirmation')
                ->count(),

            // 4. Total Pendapatan Expert
            // Asumsi: Transaction berelasi dengan Appointment, dan status sukses adalah 'paid' (atau 'berhasil' sesuaikan DB)
            'total_revenue' => Transaction::whereHas('appointment', function ($q) use ($expertId) {
                $q->where('expert_id', $expertId);
            })
                ->where('status', 'berhasil') // Pastikan string ini sesuai database ('paid' atau 'berhasil')
                ->sum('amount'),
        ];
    }
}

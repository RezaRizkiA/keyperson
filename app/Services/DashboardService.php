<?php

namespace App\Services;

use App\Models\Expert;
use App\Repositories\AppointmentRepository;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Appointment;
use App\Models\Client;
use Carbon\Carbon;

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
            'total_experts' => Expert::count(),
            'total_users' => User::count(),
            'total_institutions' => Client::count(),
            'total_appointments' => Appointment::count(),
            'total_revenue' => Transaction::where('status', 'berhasil')->sum('amount'),
            'pending_appointments' => Appointment::where('status', 'need_confirmation')->count(),
            'appointment_trends' => $this->getAppointmentTrends(),
            'quick_schedule' => $this->getQuickSchedule(),
            'recent_bookings' => $this->getRecentBookings(),
        ];
    }

    /**
     * Get appointment trends data for the last 30 days
     * Returns daily booking counts for chart visualization
     */
    protected function getAppointmentTrends()
    {
        $thirtyDaysAgo = now()->subDays(30)->startOfDay();
        
        $bookings = Appointment::where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero counts
        $trends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $bookings->firstWhere('date', $date)?->count ?? 0;
            $trends[] = [
                'date' => $date,
                'count' => $count,
            ];
        }

        return $trends;
    }

    /**
     * Get upcoming appointments for quick schedule view
     * Returns next 5 appointments with expert and user details
     */
    protected function getQuickSchedule()
    {
        return Appointment::with(['expert.user', 'user'])
            ->whereIn('status', ['confirmed', 'need_confirmation'])
            ->where('date_time', '>=', now())
            ->orderBy('date_time')
            ->limit(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->expert->user->name ?? 'Expert',
                    'type' => $appointment->topic ?? 'Consultation',
                    'date_time' => $appointment->date_time,
                    'status' => $appointment->status,
                ];
            });
    }

    /**
     * Get recent booking requests for admin review
     * Returns last 10 appointments with detailed information
     */
    protected function getRecentBookings()
    {
        return Appointment::with(['expert.user', 'user.client'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'expert' => [
                        'name' => $appointment->expert->user->name ?? 'Unknown',
                        'avatar' => $appointment->expert->user->picture_url ?? null,
                    ],
                    'client' => [
                        'institution' => $appointment->user->client->company_name ?? $appointment->user->name,
                    ],
                    'date_time' => $appointment->date_time,
                    'status' => $appointment->status,
                ];
            });
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

    public function getUserStats($userId)
    {
        return [
            // 1. Total Booking (History)
            'total_bookings' => Appointment::where('user_id', $userId)->count(),

            // 2. Sesi Mendatang (Penting agar user tidak lupa)
            'upcoming_sessions' => Appointment::where('user_id', $userId)
                ->whereIn('status', ['confirmed', 'progress'])
                ->where('date_time', '>=', now())
                ->count(),

            // 3. Menunggu Pembayaran (Action Needed)
            'pending_payments' => Appointment::where('user_id', $userId)
                ->where('payment_status', 'pending') // Asumsi kolom payment_status
                ->count(),

            // 4. Total Pengeluaran (Total Spent)
            // Mengambil dari transaksi sukses yang terkait user ini
            'total_spent' => Transaction::whereHas('appointment', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
                ->where('status', 'paid') // Atau 'settlement' / 'success' sesuai midtrans
                ->sum('amount'),
        ];
    }
}

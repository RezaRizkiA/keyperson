<?php

namespace App\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentRepository
{
    public function getAllForAdmin($perPage = 10)
    {
        return Appointment::query()
            ->select([
                'id',
                'user_id',
                'expert_id',
                'status',
                'payment_status',
                'created_at',
            ])
            ->with([
                'user:id,name,email',
                'expert:id,user_id',
                'expert.user:id,name,email',
            ])
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    /**
     * Get paginated appointments for admin with filters and stats
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedAppointmentsForAdmin(array $filters = [], int $perPage = 15)
    {
        $query = Appointment::query()
            ->with([
                'user:id,name,email',
                'user.client:id,user_id,company_name',
                'expert:id,user_id',
                'expert.user:id,name,email',
            ])
            ->select([
                'id',
                'user_id',
                'expert_id',
                'skill_id',
                'date_time',
                'status',
                'payment_status',
                'price',
                'hours',
                'created_at',
                'updated_at',
            ]);

        // Search filter - search by client name, expert name, or company
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('expert.user', function ($expertQuery) use ($search) {
                        $expertQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user.client', function ($clientQuery) use ($search) {
                        $clientQuery->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Order by created_at desc
        $query->orderBy('date_time', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Get stats for appointments
     */
    public function getStats(?int $expertId = null): array
    {
        $query = Appointment::query();

        if ($expertId) {
            $query->where('expert_id', $expertId);
        }

        return [
            'total' => (clone $query)->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'scheduled' => (clone $query)->where('status', 'confirmed')->count(),
            'cancelled' => (clone $query)->where('status', 'declined')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
        ];
    }

    /**
     * Get appointments for a specific date range (for calendar view)
     * Returns all appointments without pagination
     *
     * @param  string|Carbon  $startDate
     * @param  string|Carbon  $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAppointmentsForDateRange($startDate, $endDate, array $filters = [], ?int $expertId = null)
    {
        $query = Appointment::query()
            ->with([
                'user:id,name,email',
                'user.client:id,user_id,company_name',
                'expert:id,user_id',
                'expert.user:id,name,email',
            ])
            ->select([
                'id',
                'user_id',
                'expert_id',
                'skill_id',
                'date_time',
                'status',
                'payment_status',
                'price',
                'hours',
                'created_at',
                'updated_at',
            ])
            ->whereBetween('date_time', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('expert.user', function ($expertQuery) use ($search) {
                        $expertQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user.client', function ($clientQuery) use ($search) {
                        $clientQuery->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply expert filter (from Admin filter or Expert view)
        if ($expertId) {
            $query->where('expert_id', $expertId);
        } elseif (! empty($filters['expert'])) {
            $query->where('expert_id', $filters['expert']);
        }

        // Order by date_time ascending for calendar display
        $query->orderBy('date_time', 'asc');

        return $query->get();
    }

    /**
     * Query khusus untuk Expert.
     * Hanya mengambil data dimana expert_id sesuai dengan yang login.
     */
    public function getAllForExpert($expertId, $filters = [], $perPage = 10)
    {
        $query = Appointment::query()
            // FILTER UTAMA: Hanya data milik expert ini
            ->where('expert_id', $expertId)

            ->select([
                'id',
                'user_id',
                'expert_id',
                'skill_id',
                'date_time',
                'status',
                'payment_status',
                'topic',
                'location_url',
                'created_at',
            ])
            ->with([
                'user:id,name,email,picture',
                'expert:id,user_id',
                'expert.user:id,name,email',
                'skill:id,name,sub_category_id',
                'skill.subCategory:id,name',
            ]);

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Apply status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest('date_time')
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    public function getAllForUser($userId, $perPage = 10)
    {
        return Appointment::query()
            ->where('user_id', $userId)
            ->select([
                'id',
                'expert_id',
                'skill_id', // Penting agar expert tau topiknya apa
                'date_time',
                'status',
                'payment_status',
                'topic', // Catatan user
                'location_url', // Link meeting
                'created_at',
            ])
            ->with([
                'expert.user:id,name,email,picture',
                'skill:id,name,sub_category_id',
                'skill.subCategory:id,name',
            ])
            ->latest('date_time')
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    /**
     * Mengambil detail appointment lengkap beserta relasinya.
     * Digunakan untuk Admin, Expert, User, dan Client.
     */
    public function getAppointmentDetail($id)
    {
        return Appointment::with([
            'user',                  // Data Employee/User yg booking
            'expert.user',           // Data Personal Expert (Nama, Foto)
            'transactions',           // Data Pembayaran (untuk Admin & Client)
            'skill.subCategory.category',
        ])
            ->findOrFail($id);
    }

    // Ambil data raw untuk Calendar (Tanpa pagination)
    public function getAllForCalendar($userId = null, $expertId = null, $isAdmin = false)
    {
        $query = Appointment::select('id', 'user_id', 'expert_id', 'date_time', 'status', 'appointment');

        if ($isAdmin) {
            // ADMIN: Ambil Semua + Load relasi User & Expert (untuk judul event)
            $query->with([
                'user:id,name',
                'expert.user:id,name',
            ]);
        } elseif ($expertId) {
            $query->where('expert_id', $expertId)->with('user:id,name');
        } else {
            $query->where('user_id', $userId)->with('expert.user:id,name');
        }

        return $query->get();
    }

    /**
     * Mencari appointment spesifik beserta relasi user & expert
     * Digunakan oleh Service untuk validasi & notifikasi
     */
    public function findWithRelations($id)
    {
        // Kita butuh data user (Client) dan expert.user (Expert)
        // untuk kirim email notifikasi nama pengirim/penerima.
        return Appointment::with(['user', 'expert.user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Appointment::create([
            'user_id' => $data['user_id'],
            'expert_id' => $data['expert_id'],
            'skill_id' => $data['skill_id'] ?? null,
            'date_time' => $data['date_time'],
            'hours' => $data['hours'],
            'price' => $data['price'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'location_url' => null,
            'google_calendar_event_id' => null,
            'topic' => $data['topic'] ?? null,
            'type' => $data['type'],
            'guests' => $data['guests'] ?? null,
        ]);
    }

    // 1. Ambil semua appointment yang "Active" untuk expert tertentu
    // Dipakai untuk generate tampilan kalender di frontend
    public function getActiveAppointmentsByExpert($expertId)
    {
        return Appointment::where('expert_id', $expertId)
            ->where('status', '!=', 'declined') // Status declined dianggap kosong
            ->whereDate('date_time', '>=', Carbon::today())
            ->get(['date_time', 'hours']);
    }

    // 2. Cek apakah Slot Waktu tersedia (Conflict Checker)
    public function isSlotAvailable($expertId, $startDateTime, $endDateTime)
    {
        return ! Appointment::where('expert_id', $expertId)
            ->where('status', '!=', 'declined')
            ->where(function ($query) use ($startDateTime, $endDateTime) {
                // Logika overlap waktu
                $query->where('date_time', '<', $endDateTime)
                    ->whereRaw('DATE_ADD(date_time, INTERVAL hours HOUR) > ?', [$startDateTime]);
            })
            ->exists();
    }

    /**
     * Melakukan update data appointment
     */
    public function update(Appointment $appointment, array $data)
    {
        $appointment->update($data);

        return $appointment->refresh(); // Return data terbaru
    }

    // Ambil appointment untuk Client (User biasa)
    public function getForClient($userId, $perPage = 5)
    {
        return Appointment::where('user_id', $userId)
            ->with(['expert.user:id,name,email,picture']) // Eager load efisien
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    // Ambil appointment untuk Expert
    public function getForExpert($expertId, $perPage = 5)
    {
        return Appointment::where('expert_id', $expertId)
            ->with(['user:id,name,email,picture'])
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    // Hitung total appointment
    public function countForUser($userId, $expertId = null)
    {
        if ($expertId) {
            return Appointment::where('expert_id', $expertId)->count();
        }

        return Appointment::where('user_id', $userId)->count();
    }
}

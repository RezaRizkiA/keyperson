<?php

namespace App\Repositories;

use App\Models\Appointment;

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
                'created_at'
            ])
            ->with([
                'user:id,name,email',
                'expert:id,user_id',
                'expert.user:id,name,email'
            ])
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    /**
     * Query khusus untuk Expert.
     * Hanya mengambil data dimana expert_id sesuai dengan yang login.
     */
    public function getAllForExpert($expertId, $perPage = 10)
    {
        return Appointment::query()
            // FILTER UTAMA: Hanya data milik expert ini
            ->where('expert_id', $expertId)

            ->select([
                'id',
                'user_id',
                'skill_id', // Penting agar expert tau topiknya apa
                'date_time',
                'status',
                'payment_status',
                'topic', // Catatan user
                'location_url', // Link meeting
                'created_at'
            ])
            // Eager Load: Expert butuh data User (Client) dan Skill (Kategori)
            ->with([
                'user:id,name,email,picture', // Ambil foto client juga buat UX bagus
                'skill:id,name,sub_category_id',
                'skill.subCategory:id,name'
            ])
            ->latest('date_time') // Urutkan berdasarkan jadwal meeting terdekat/terbaru
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
                'expert.user:id,name'
            ]);
        } else if ($expertId) {
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

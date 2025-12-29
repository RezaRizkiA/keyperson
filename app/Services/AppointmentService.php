<?php

namespace App\Services;

use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentStatusChanged;
use App\Models\Expert;
use App\Models\User;
use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentService
{
    protected $repo;

    protected $calendarService;

    protected $quotaService;

    public function __construct(
        AppointmentRepository $repo,
        GoogleCalendarService $calendarService,
        QuotaService $quotaService
    ) {
        $this->repo = $repo;
        $this->calendarService = $calendarService;
        $this->quotaService = $quotaService;
    }

    public function getAllForAdmin($perPage = 10)
    {
        return $this->repo->getAllForAdmin($perPage);
    }

    public function getPaginatedAppointments(array $filters = [], int $perPage = 15)
    {
        return $this->repo->getPaginatedAppointmentsForAdmin($filters, $perPage);
    }

    public function getAppointmentStats(?int $expertId = null): array
    {
        return $this->repo->getStats($expertId);
    }

    /**
     * Get appointments for calendar view without pagination
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAppointmentsForCalendar(array $filters = [], ?int $expertId = null)
    {
        // Use provided date range or default to current month
        $startDate = $filters['start_date'] ?? Carbon::now()->startOfMonth();
        $endDate = $filters['end_date'] ?? Carbon::now()->endOfMonth();

        return $this->repo->getAppointmentsForDateRange(
            $startDate,
            $endDate,
            $filters,
            $expertId
        );
    }

    public function getAllForExpert($expertId, $filters = [], $perPage = 10)
    {
        return $this->repo->getAllForExpert($expertId, $filters, $perPage);
    }

    public function getAllForUser($userId, $perPage = 10)
    {
        return $this->repo->getAllForUser($userId, $perPage);
    }

    public function getAppointmentDetail($id)
    {
        $appointment = $this->repo->getAppointmentDetail($id);

        $user = Auth::user();
        $userRoles = $user->roles ?? []; // array roles

        // cek permission

        // role admin
        if (in_array('administrator', $userRoles)) {
            return $appointment;
        }

        // role expert
        if ($appointment->expert && $appointment->expert->user_id === $user->id) {
            return $appointment;
        }

        // user / employee
        if ($appointment->user_id === $user->id) {
            return $appointment;
        }

        // client / company -> b2b2c ( status backlog )
        // if (in_array('client', $userRoles)) {
        //     if ($appointment->user->client_id === $user->client_id) {
        //         return $appointment;
        //     }
        // }

        abort(403, 'Unauthorized access to this appointment.');
    }

    // 1. Logic Generate "Booked Slots" untuk Frontend
    // Memindahkan loop foreach yang panjang dari Controller lama
    public function getBusySlots($expertId)
    {
        $appointments = $this->repo->getActiveAppointmentsByExpert($expertId);
        $bookedSlots = [];

        foreach ($appointments as $app) {
            $start = Carbon::parse($app->date_time);
            for ($i = 0; $i < $app->hours; $i++) {
                $current = $start->copy()->addHours($i);
                $dateKey = $current->format('Y-m-d');
                $timeKey = $current->format('H:i');

                if (! isset($bookedSlots[$dateKey])) {
                    $bookedSlots[$dateKey] = [];
                }
                $bookedSlots[$dateKey][] = $timeKey;
            }
        }

        return $bookedSlots;
    }

    // 2. Logic Create dengan Transaction & Locking
    public function createAppointment($data, $userId)
    {
        return DB::transaction(function () use ($data, $userId) {

            // A. LOCKING (PENTING! Jangan dibuang)
            // Kita lock row expert ini agar tidak ada proses lain yang booking di detik yang sama
            $expert = Expert::lockForUpdate()->findOrFail($data['expert_id']);

            // B. Hitung Start & End Time (date_time is combined: "YYYY-MM-DD HH:mm:ss")
            $startDateTime = Carbon::parse($data['date_time']);
            $endDateTime = $startDateTime->copy()->addHours($data['hours']);

            // C. Cek Bentrok via Repository
            if (! $this->repo->isSlotAvailable($expert->id, $startDateTime, $endDateTime)) {
                throw new Exception('Maaf, slot waktu ini baru saja diambil orang lain.');
            }

            // D. Hitung Harga (Per-Pax untuk Group Booking)
            // Logic: totalPrice = expert.price * hours * (1 + jumlah_guests)
            $bookingType = $data['type'] ?? 'individual';
            $guests = ($bookingType === 'group' && ! empty($data['guests'])) ? $data['guests'] : [];
            $guestsCount = is_array($guests) ? count($guests) : 0;

            // Total participants = 1 (user yang booking) + guests
            $totalPax = 1 + $guestsCount;
            $totalPrice = $expert->price * $data['hours'] * $totalPax;

            // E. Siapkan Data Final
            $finalData = [
                'user_id' => $userId,
                'expert_id' => $expert->id,
                'skill_id' => $data['skill_id'] ?? null,
                'date_time' => $startDateTime,
                'hours' => $data['hours'],
                'price' => $totalPrice,
                'topic' => $data['topic'],
                'type' => $bookingType,
                'guests' => ($bookingType === 'group' && ! empty($guests)) ? $guests : null,
                'status' => 'pending',
                'payment_status' => 'pending',
            ];

            // === B2B QUOTA CHECK ===
            $user = User::with('company.quota')->findOrFail($userId);

            // Cek apakah user adalah karyawan perusahaan (B2B)
            $isCorporateBooking = $user->isCorporateMember() &&
                                  $user->company &&
                                  $user->company->quota;

            if ($isCorporateBooking) {
                // Simpan appointment dulu untuk mendapatkan ID
                $appointment = $this->repo->create($finalData);

                // Potong quota menggunakan QuotaService (dengan ledger logging)
                $success = $this->quotaService->deductQuota(
                    $user->company,
                    $totalPrice,
                    $appointment,
                    $user
                );

                if (! $success) {
                    throw new Exception('Kuota perusahaan tidak mencukupi. Silakan hubungi HRD.');
                }

                // Update status langsung confirmed (bypass payment)
                $appointment->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid', // via corporate quota
                ]);

                return $appointment;
            }
            // === END B2B LOGIC ===

            // F. Simpan
            return $this->repo->create($finalData);
        });
    }

    /**
     * Handle Update Status Appointment
     */
    public function updateStatus($appointmentId, $newStatus)
    {
        // 1. Ambil Data
        $appointment = $this->repo->findWithRelations($appointmentId);
        $user = Auth::user();

        // 2. Authorization Check (Refactor: Tambahkan Admin check)
        // Kita butuh tau siapa yang melakukan aksi ini untuk kirim email ke "lawan bicara"
        $isExpert = optional($appointment->expert)->user_id === $user->id;
        $isClient = $appointment->user_id === $user->id;
        $isAdmin = $user->hasRole('administrator'); // Asumsi ada logic role check

        if (! $isExpert && ! $isClient && ! $isAdmin) {
            throw new Exception('You are not authorized to update this appointment.', 403);
        }

        // 3. Update Status di Database
        $this->repo->update($appointment, ['status' => $newStatus]);

        // 4. Handle Google Calendar & Email Notification
        $this->handlePostUpdateActions($appointment, $newStatus, $isExpert);

        return $appointment;
    }

    /**
     * Private method untuk logic "efek samping" (Calendar & Email)
     * Agar method updateStatus tetap bersih
     */
    private function handlePostUpdateActions($appointment, $status, $isActorExpert)
    {
        // Jika tidak ada event ID, skip calendar sync
        if (! $appointment->google_calendar_event_id) {
            return;
        }

        try {
            $clientUser = $appointment->user; // Client pemilik event calendar
            $expertName = $appointment->expert->user->name;

            // Ambil object Event dari Google
            $event = $this->calendarService->getEvent($clientUser, $appointment->google_calendar_event_id);

            switch ($status) {
                case 'progress': // Confirmed
                    // Update Judul Calendar
                    $event->setSummary("Appointment with {$expertName} (Confirmed)");
                    $this->calendarService->updateEvent($clientUser, $appointment->google_calendar_event_id, $event);

                    // Kirim Email Konfirmasi ke Client
                    Mail::to($clientUser->email)->send(new AppointmentConfirmed($appointment));
                    break;

                case 'declined': // Cancelled
                    // === B2B REFUND: Kembalikan quota jika booking menggunakan quota perusahaan ===
                    if ($appointment->payment_status === 'paid' && $clientUser->isCorporateMember()) {
                        $this->quotaService->refundQuota(
                            $clientUser->company,
                            $appointment->price,
                            $appointment,
                            Auth::user() // User yang membatalkan
                        );
                    }
                    // === END B2B REFUND ===

                    // Hapus dari Calendar
                    $this->calendarService->deleteEvent($clientUser, $appointment->google_calendar_event_id);

                    // Hapus token event dari DB lokal
                    $this->repo->update($appointment, ['google_calendar_event_id' => null]);

                    // Kirim Email Notifikasi
                    // Logic: Jika Expert yg cancel -> email ke Client. Jika Client yg cancel -> email ke Expert.
                    $recipient = $isActorExpert ? $clientUser : $appointment->expert->user;
                    Mail::to($recipient->email)->send(new AppointmentStatusChanged($appointment, 'declined'));
                    break;

                case 'completed':
                    // Update Judul Calendar
                    $event->setSummary("Appointment with {$expertName} (Completed)");
                    $this->calendarService->updateEvent($clientUser, $appointment->google_calendar_event_id, $event);
                    break;
            }
        } catch (Exception $e) {
            // Log error tapi jangan gagalkan proses update status utama
            // Atau throw exception jika ingin controller tau ada error calendar
            throw new Exception('Status updated, but Google Calendar sync failed: '.$e->getMessage());
        }
    }
}

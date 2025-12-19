<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Services\GoogleCalendarService;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentStatusChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Exception;

class AppointmentService
{
    protected $repo;
    protected $calendarService;

    public function __construct(
        AppointmentRepository $repo,
        GoogleCalendarService $calendarService
    ) {
        $this->repo = $repo;
        $this->calendarService = $calendarService;
    }

    public function getAllForAdmin($perPage = 10)
    {
        return $this->repo->getAllForAdmin($perPage);
    }

    public function getAllForExpert($expertId, $perPage = 10)
    {
        return $this->repo->getAllForExpert($expertId, $perPage);
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
        };

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
        $isAdmin  = $user->hasRole('administrator'); // Asumsi ada logic role check

        if (!$isExpert && !$isClient && !$isAdmin) {
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
        if (!$appointment->google_calendar_event_id) {
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
            throw new Exception('Status updated, but Google Calendar sync failed: ' . $e->getMessage());
        }
    }
}

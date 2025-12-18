<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentLinkUpdated;
use App\Mail\AppointmentRescheduled;
use App\Mail\AppointmentStatusChanged;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    protected $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Controller terima data matang dari Service
        $appointments = $this->service->getAllForAdmin();

        return Inertia::render('Administrator/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    public function show($id)
    {
        $appointment = $this->service->getAppointmentDetail($id);

        $user = Auth::user();
        $roles = $user->roles ?? [];

        // --- ROLE VIEW ROUTING ---
        // A. Administrator View
        if (in_array('administrator', $roles)) {
            return Inertia::render('Administrator/Appointments/Show', [
                'appointment' => $appointment
            ]);
        }

        // B. Expert View
        if (in_array('expert', $roles)) {
            return Inertia::render('Expert/Appointments/Show', [
                'appointment' => $appointment
            ]);
        }

        // C. User View (Default fallback)
        return Inertia::render('User/Appointments/Show', [
            'appointment' => $appointment
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:pending,need_confirmation,confirmed,progress,completed,declined,edited',
        ]);

        try {
            // 2. Panggil Service
            $this->service->updateStatus($id, $request->status);
            return redirect()->back()->with('success', 'Appointment status updated successfully.');
        } catch (\Exception $e) {
            if ($e->getCode() === 403) {
                return redirect()->back()->withErrors(['authorization' => $e->getMessage()]);
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function reschedule(Request $request, $id, GoogleCalendarService $calendarService)
    {
        $request->validate([
            'date_time' => 'required|date|after:now',
        ]);

        $appointment = Appointment::with(['user', 'expert.user'])->findOrFail($id);

        // 3. Otorisasi: Hapus pengecekan Expert ID. 
        // Kita asumsikan Middleware 'role:admin' di route sudah menangani keamanan.

        try {
            $newDateTime = Carbon::parse($request->date_time);

            // 4. Update Google Calendar (Opsional/Best Effort)
            // Note: Ini mungkin tricky kalau Admin tidak punya token akses ke kalender user.
            // Sebaiknya dibungkus try-catch terpisah agar tidak membatalkan update DB jika gagal.
            if ($appointment->google_calendar_event_id) {
                try {
                    // Pastikan $calendarService bisa handle update by Admin
                    $event = $calendarService->getEvent($appointment->user, $appointment->google_calendar_event_id);

                    $startTime = new EventDateTime();
                    $startTime->setDateTime($newDateTime->toRfc3339String());
                    $event->setStart($startTime);

                    $endTime = new EventDateTime();
                    // Asumsi durasi default 1 jam jika kolom hours tidak ada/null
                    $hours = $appointment->hours ?? 1;
                    $endTime->setDateTime($newDateTime->copy()->addHours($hours)->toRfc3339String());
                    $event->setEnd($endTime);

                    $event->setSummary('Appointment with ' . $appointment->expert->user->name . ' (Rescheduled by Admin)');

                    $calendarService->updateEvent($appointment->user, $appointment->google_calendar_event_id, $event);
                } catch (\Exception $calEx) {
                    // Log error kalender tapi lanjut update database
                    Log::error("GCal update failed: " . $calEx->getMessage());
                }
            }

            // 5. Update Database
            $appointment->date_time = $newDateTime;

            // Opsional: Jika status 'declined' atau 'completed', kembalikan ke 'confirmed'
            if (in_array($appointment->status, ['declined', 'completed'])) {
                $appointment->status = 'confirmed';
            }

            $appointment->save();

            // 6. Kirim Email Notifikasi ke User & Expert
            // Cek dulu apakah user punya email valid
            if ($appointment->user && $appointment->user->email) {
                Mail::to($appointment->user->email)->send(new AppointmentRescheduled($appointment));
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update schedule: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Appointment schedule has been updated successfully.');
    }

    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'location_url' => 'required|url|max:255',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Pastikan yang update adalah Expert yang bersangkutan (Security)
        if (Auth::user()->id !== $appointment->expert->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->location_url = $request->location_url;
        $appointment->save();

        if ($appointment->user && $appointment->user->email) {
            try {
                Mail::to($appointment->user->email)->send(new AppointmentLinkUpdated($appointment));
            } catch (\Exception $e) {
                // Log error jika email gagal kirim, tapi jangan gagalkan proses simpan
                Log::error('Gagal kirim email update link: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Meeting link updated successfully.');
    }
}

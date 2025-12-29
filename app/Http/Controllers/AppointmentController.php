<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Mail\AppointmentLinkUpdated;
use App\Mail\AppointmentRescheduled;
use App\Models\Appointment;
use App\Models\Expert;
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

    public function index(Request $request)
    {
        $user = Auth::user();
        $roles = $user->roles ?? [];

        // --- A. JIKA ADMINISTRATOR ---
        if (in_array('administrator', $roles)) {
            try {
                $filters = $request->only(['search', 'status', 'expert', 'view_mode', 'start_date', 'end_date']);

                // Check if calendar view mode
                if (isset($filters['view_mode']) && $filters['view_mode'] === 'calendar') {
                    // Calendar view: no pagination, get all appointments for date range
                    $appointments = $this->service->getAppointmentsForCalendar($filters);

                    // Transform without pagination wrapper
                    $transformedAppointments = $appointments->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'client_name' => $appointment->user->name ?? 'Unknown',
                            'company_name' => $appointment->user->client->company_name ?? null,
                            'expert_name' => $appointment->expert->user->name ?? 'Unknown',
                            'date_time' => $appointment->date_time,
                            'status' => $appointment->status,
                            'payment_status' => $appointment->payment_status,
                            'price' => $appointment->price,
                            'hours' => $appointment->hours,
                        ];
                    })->values(); // Reset array keys
                } else {
                    // List view: paginated as before
                    $appointments = $this->service->getPaginatedAppointments($filters);

                    // Transform with pagination
                    $transformedAppointments = $appointments->through(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'client_name' => $appointment->user->name ?? 'Unknown',
                            'company_name' => $appointment->user->client->company_name ?? null,
                            'expert_name' => $appointment->expert->user->name ?? 'Unknown',
                            'date_time' => $appointment->date_time,
                            'status' => $appointment->status,
                            'payment_status' => $appointment->payment_status,
                            'price' => $appointment->price,
                            'hours' => $appointment->hours,
                        ];
                    });
                }

                // Get stats
                $stats = $this->service->getAppointmentStats();

                return Inertia::render('Administrator/Appointments/Index', [
                    'appointments' => $transformedAppointments,
                    'stats' => $stats,
                    'filters' => $filters,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to load appointments: '.$e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->route('dashboard.index')
                    ->withErrors(['error' => 'Failed to load appointments.']);
            }
        }

        // --- B. JIKA EXPERT ---
        if (in_array('expert', $roles)) {
            $expert = $user->expert;

            if (! $expert) {
                return redirect()->route('dashboard.index')->with('error', 'Profile expert belum aktif.');
            }

            $filters = $request->only(['search', 'status', 'view_mode', 'start_date', 'end_date']);

            // Check if calendar view mode
            if (isset($filters['view_mode']) && $filters['view_mode'] === 'calendar') {
                // Calendar view: no pagination, get all appointments for date range
                $appointments = $this->service->getAppointmentsForCalendar($filters, $expert->id);

                // Transform without pagination wrapper
                $transformedAppointments = $appointments->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'client_name' => $appointment->user->name ?? 'Unknown',
                        'company_name' => $appointment->user->client->company_name ?? null,
                        'expert_name' => $appointment->expert->user->name ?? 'Unknown',
                        'date_time' => $appointment->date_time,
                        'status' => $appointment->status,
                        'payment_status' => $appointment->payment_status,
                        'price' => $appointment->price,
                        'hours' => $appointment->hours,
                        'user' => $appointment->user,
                        'skill' => $appointment->skill?->load('subCategory'),
                    ];
                })->values();
            } else {
                // List view: paginated
                $appointments = $this->service->getAllForExpert($expert->id, $filters);

                // Transform with pagination
                $transformedAppointments = $appointments->through(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'client_name' => $appointment->user->name ?? 'Unknown',
                        'company_name' => $appointment->user->client->company_name ?? null,
                        'expert_name' => $appointment->expert->user->name ?? 'Unknown',
                        'date_time' => $appointment->date_time,
                        'status' => $appointment->status,
                        'payment_status' => $appointment->payment_status,
                        'price' => $appointment->price,
                        'hours' => $appointment->hours,
                        'user' => $appointment->user,
                        'skill' => $appointment->skill?->load('subCategory'),
                    ];
                });
            }

            // Get stats for this expert only
            $stats = $this->service->getAppointmentStats($expert->id);

            return Inertia::render('Expert/Appointments/Index', [
                'appointments' => $transformedAppointments,
                'stats' => $stats,
                'filters' => $filters,
            ]);
        }

        // --- C. JIKA CLIENT/USER BIASA ---
        $appointments = $this->service->getAllForUser($user->id);

        return Inertia::render('User/Appointments/Index', [
            'appointments' => $appointments,
        ]);
    }

    public function show($id)
    {
        $appointment = $this->service->getAppointmentDetail($id);

        $user = Auth::user();
        $roles = $user->roles ?? [];

        if (in_array('administrator', $roles)) {
            return Inertia::render('Administrator/Appointments/Show', [
                'appointment' => $appointment,
            ]);
        }

        // B. Expert View
        if (in_array('expert', $roles)) {
            return Inertia::render('Expert/Appointments/Show', [
                'appointment' => $appointment,
            ]);
        }

        // C. User View - include pending transaction for "Continue Payment" button
        $pendingTransaction = \App\Models\Transaction::where('appointment_id', $appointment->id)
            ->where('status', 'pending')
            ->where('expired_date', '>', now())
            ->first();

        return Inertia::render('User/Appointments/Show', [
            'appointment' => $appointment,
            'pendingTransaction' => $pendingTransaction,
        ]);
    }

    public function create($slug)
    {
        // Load expert with user AND skills for skill selection
        $expert = Expert::with(['user', 'skills'])->where('slug', $slug)->firstOrFail();

        // Ambil slot sibuk dari Service
        $bookedSlots = $this->service->getBusySlots($expert->id);

        // Get skill_id from query param (passed from portal flow)
        $skillId = request('skill_id');

        return Inertia::render('User/Appointments/Booking', [
            'expert' => $expert,
            'bookedSlots' => $bookedSlots,
            'backUrl' => route('experts.show', $expert->slug),
            'skillId' => $skillId ? (int) $skillId : null,
        ]);
    }

    // POST: Simpan Booking
    public function store(StoreAppointmentRequest $request)
    {
        try {
            $data = $request->validated();

            // Panggil Service
            $appointment = $this->service->createAppointment($data, Auth::id());

            // === B2B CHECK: Skip payment if already paid via corporate quota ===
            if ($appointment->payment_status === 'paid') {
                return redirect()->route('dashboard.appointments.show', $appointment->id)
                    ->with('success', 'Booking berhasil! Kuota perusahaan telah dipotong.');
            }
            // === END B2B CHECK ===

            // Redirect ke Payment (untuk retail user)
            return redirect()->route('payment.create', $appointment->id)
                ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            // Tangkap error (misal: slot penuh saat lock)
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
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

                    $startTime = new EventDateTime;
                    $startTime->setDateTime($newDateTime->toRfc3339String());
                    $event->setStart($startTime);

                    $endTime = new EventDateTime;
                    // Asumsi durasi default 1 jam jika kolom hours tidak ada/null
                    $hours = $appointment->hours ?? 1;
                    $endTime->setDateTime($newDateTime->copy()->addHours($hours)->toRfc3339String());
                    $event->setEnd($endTime);

                    $event->setSummary('Appointment with '.$appointment->expert->user->name.' (Rescheduled by Admin)');

                    $calendarService->updateEvent($appointment->user, $appointment->google_calendar_event_id, $event);
                } catch (\Exception $calEx) {
                    // Log error kalender tapi lanjut update database
                    Log::error('GCal update failed: '.$calEx->getMessage());
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
            return back()->withErrors(['error' => 'Failed to update schedule: '.$e->getMessage()]);
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
                Log::error('Gagal kirim email update link: '.$e->getMessage());
            }
        }

        return back()->with('success', 'Meeting link updated successfully.');
    }
}

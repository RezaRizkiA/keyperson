<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expert;
// use App\Services\GoogleCalendarService; // Tidak lagi digunakan di sini
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExpertController extends Controller
{
    public function make_appointment($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);

        $appointments = Appointment::where('expert_id', $expert_id)
            ->where('status', '!=', 'declined')
            ->whereDate('date_time', '>=', Carbon::today())
            ->get(['date_time', 'hours']);

        $bookedSlots = [];

        foreach ($appointments as $app) {
            $start = Carbon::parse($app->date_time);
            for ($i = 0; $i < $app->hours; $i++) {
                $current = $start->copy()->addHours($i);
                $dateKey = $current->format('Y-m-d');
                $timeKey = $current->format('H:i');

                if (!isset($bookedSlots[$dateKey])) {
                    $bookedSlots[$dateKey] = [];
                }
                $bookedSlots[$dateKey][] = $timeKey;
            }
        }

        // Tangkap URL sebelumnya (jika ada)
        $backUrl = request('back');

        return Inertia::render('Client/MakeAppointment', [
            'expert' => $expert,
            'backUrl' => $backUrl,
            'bookedSlots' => $bookedSlots,
        ]);
    }

    public function make_appointment_post(Request $request, $expert_id) // GoogleCalendarService dihapus
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'hours' => 'required|integer|min:1|max:8',
            'topic' => 'required|string|max:1000',
        ]);

        $expert = Expert::findOrFail($expert_id);

        try {
            return DB::transaction(function () use ($request, $expert, $expert_id) {
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', "{$request->date} {$request->time}");
                $endDateTime = $startDateTime->copy()->addHours($request->hours);

                $conflict = Appointment::where('expert_id', $expert_id)
                    ->where('status', '!=', 'declined')
                    ->where(function ($query) use ($startDateTime, $endDateTime) {
                        $query->where('date_time', '<', $endDateTime)
                            ->whereRaw("DATE_ADD(date_time, INTERVAL hours HOUR) > ?", [$startDateTime]);
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    throw new \Exception('Slot waktu ini baru saja booking orang lain.');
                }

                // Buat appointment dengan status pembayaran 'pending'
                $appointment = Appointment::create([
                    'user_id'     => Auth::user()->id,
                    'expert_id'   => $expert_id,
                    'appointment' => $request->topic,
                    'date_time'   => $startDateTime,
                    'hours'       => $request->hours,
                    'price'       => $expert->price,
                    'status'      => 'need_confirmation',
                    'payment_status' => 'pending', // Status pembayaran awal
                ]);

                // Arahkan ke halaman pembayaran
                return redirect()->route('payment.show', ['appointment' => $appointment->id]);
            });
        } catch (\Exception $e) {
            // Jika konflik atau error, kembalikan ke halaman sebelumnya dengan pesan
            return back()->withErrors(['time' => $e->getMessage()]);
        }
    }
}

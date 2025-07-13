<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expert;
// use App\Services\GoogleCalendarService; // Tidak lagi digunakan di sini
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    public function make_appointment($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);
        return view('appointment', compact('expert'));
    }

    public function make_appointment_post(Request $request, $expert_id) // GoogleCalendarService dihapus
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $dateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            "{$request->date} {$request->time}",
            config('app.timezone')
        );
        $expert = Expert::with('user')->findOrFail($expert_id);
        // Buat appointment dengan status pembayaran 'pending'
        $appointment = Appointment::create([
            'user_id'     => Auth::user()->id,
            'expert_id'   => $expert_id,
            'appointment' => $request->appointment,
            'date_time'   => $dateTime,
            'hours'       => $request->hours,
            'price'       => $expert->price,
            'status'      => 'need_confirmation',
            'payment_status' => 'pending', // Status pembayaran awal
        ]);

        // Arahkan ke halaman pembayaran
        return redirect()->route('payment.show', ['appointment' => $appointment->id]);
    }
}

<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expert;
use App\Models\Appoinment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    //
    public function make_appointment($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);

        return view('appointment', compact('expert'));
    }

    public function make_appointment_post(Request $request, $expert_id)
    {
        // (opsional tapi sangat disarankan)
        $request->validate([
            'date' => 'required|date',            // 2025-06-14
            'time' => 'required|date_format:H:i', // 23:28
        ]);

        $dateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            "{$request->date} {$request->time}",
            config('app.timezone') // mis. Asia/Jakarta
        );
        // dd($dateTime);

        $expert = Expert::findOrFail($expert_id);

        $appointment = Appoinment::create([
            'user_id'    => Auth::user()->id,
            'expert_id'  => $expert_id,
            'appoinment' => $request->appoinment,
            'date_time'  => $dateTime,
            'hours'      => $request->hours,
            'price'      => $expert->price,
            'status'     => 'need_confirmation',
        ]);

        return redirect()->route('profile');
    }
}

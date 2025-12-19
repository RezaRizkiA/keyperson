<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class CalendarService
{
    /**
     * Format data appointment menjadi FullCalendar Event Object
     */
    private function formatEvents($appointments, $roleContext)
    {
        return $appointments->map(function ($appointment) use ($roleContext) {

            // Tentukan Judul Event berdasarkan siapa yang melihat
            $title = '';
            if ($roleContext === 'admin') {
                $title = $appointment->expert->user->name . ' <> ' . $appointment->user->name;
            } elseif ($roleContext === 'expert') {
                $title = 'Session w/ ' . $appointment->user->name; // Expert melihat nama Client
            } else {
                $title = 'Consultation w/ ' . $appointment->expert->user->name; // User melihat nama Expert
            }

            // Tentukan Warna berdasarkan Status
            $color = '#94a3b8'; // Default Gray
            if ($appointment->status === 'confirmed') $color = '#7c3aed'; // Violet
            if ($appointment->status === 'progress') $color = '#2563eb'; // Blue
            if ($appointment->status === 'completed') $color = '#10b981'; // Emerald
            if ($appointment->status === 'need_confirmation') $color = '#f97316'; // Orange

            // Hitung Waktu Selesai (Start + Duration)
            $start = Carbon::parse($appointment->date_time);
            $end = $start->copy()->addHours($appointment->hours);

            return [
                'id' => $appointment->id,
                'title' => $title,
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => $appointment->status,
                    'topic' => $appointment->skill->name ?? 'Consultation'
                ]
            ];
        });
    }

    public function getEventsForAdmin()
    {
        $data = Appointment::with(['user', 'expert.user'])->get();
        return $this->formatEvents($data, 'admin');
    }

    public function getEventsForExpert($expertId)
    {
        $data = Appointment::where('expert_id', $expertId)
            ->with(['user', 'skill']) // Expert butuh nama user
            ->get();
        return $this->formatEvents($data, 'expert');
    }

    public function getEventsForUser($userId)
    {
        $data = Appointment::where('user_id', $userId)
            ->with(['expert.user', 'skill']) // User butuh nama expert
            ->get();
        return $this->formatEvents($data, 'user');
    }
}

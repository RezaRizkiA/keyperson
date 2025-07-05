<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\GoogleCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateGoogleCalendarEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleCalendarService $calendarService): void
    {
        // Ensure the appointment has not already had a calendar event created
        if ($this->appointment->google_calendar_event_id) {
            Log::info('Google Calendar event already exists for appointment ' . $this->appointment->id);
            return;
        }

        // Ensure the appointment is paid
        if ($this->appointment->payment_status !== 'paid') {
            Log::warning('Attempted to create Google Calendar event for unpaid appointment ' . $this->appointment->id);
            return;
        }

        try {
            $expert = $this->appointment->expert->user;
            $client = $this->appointment->user;

            $eventData = [
                'summary' => 'Appointment with ' . $expert->name,
                'description' => $this->appointment->appointment,
                'start' => ['dateTime' => $this->appointment->date_time->toRfc3339String()],
                'end' => ['dateTime' => $this->appointment->date_time->copy()->addHours((int)$this->appointment->hours)->toRfc3339String()],
                'attendees' => [
                    ['email' => $expert->email],
                    ['email' => $client->email],
                ],
            ];

            // Assuming the GoogleCalendarService handles authentication for the user who initiated the appointment
            // or a service account. For simplicity, we'll pass the client user for now.
            $createdEvent = $calendarService->createEvent($client, $eventData);

            $this->appointment->google_calendar_event_id = $createdEvent->id;
            $this->appointment->save();

            Log::info('Google Calendar event created for appointment ' . $this->appointment->id . ' with event ID ' . $createdEvent->id);

        } catch (\Exception $e) {
            Log::error('Failed to create Google Calendar event for appointment ' . $this->appointment->id . ': ' . $e->getMessage());
            // You might want to re-queue the job or notify an admin here
        }
    }
}
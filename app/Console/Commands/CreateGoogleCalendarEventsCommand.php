<?php
namespace App\Console\Commands;

use App\Jobs\CreateGoogleCalendarEvent;
use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateGoogleCalendarEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-google-calendar-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to create Google Calendar events for paid appointments without an event ID.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Running CreateGoogleCalendarEventsCommand...');

        $appointments = Appointment::where('payment_status', 'paid')
            ->whereNull('google_calendar_event_id')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No paid appointments found without a Google Calendar event.');
            Log::info('No paid appointments found without a Google Calendar event.');
            return Command::SUCCESS;
        }

        $this->info('Found ' . $appointments->count() . ' paid appointments without a Google Calendar event.');
        Log::info('Found ' . $appointments->count() . ' paid appointments without a Google Calendar event.');

        foreach ($appointments as $appointment) {
            CreateGoogleCalendarEvent::dispatch($appointment);
            $this->info('Dispatched job for appointment ID: ' . $appointment->id);
            Log::info('Dispatched job for appointment ID: ' . $appointment->id);
        }

        $this->info('Finished dispatching Google Calendar event creation jobs.');
        Log::info('Finished dispatching Google Calendar event creation jobs.');

        return Command::SUCCESS;
    }
}

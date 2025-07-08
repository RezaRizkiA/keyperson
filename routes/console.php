<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Ditambahkan

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan perintah untuk membuat event Google Calendar
Schedule::command('app:create-google-calendar-events')->dailyAt('05:00');
Schedule::command('app:ipaymu-command')->dailyAt('05:00');

<?php

namespace App\Services;

use App\Models\User;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->setScopes(config('google-calendar.scopes'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function getClient(User $user): Client
    {
        $this->client->setAccessToken([
            'access_token' => $user->google_access_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in' => $user->google_token_expires_at->diffInSeconds(now()),
        ]);

        if (!$user->google_refresh_token) {
            Log::warning("Missing refresh token for user {$user->id}");
            throw new \Exception("Google Calendar not connected or token invalid.");
        }

        if ($this->client->isAccessTokenExpired()) {
            $newAccessToken = $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $user->update([
                'google_access_token' => $newAccessToken['access_token'],
                'google_token_expires_at' => now()->addSeconds($newAccessToken['expires_in']),
            ]);
            $this->client->setAccessToken($newAccessToken);
        }

        return $this->client;
    }

    public function createEvent(User $user, array $eventData): Event
    {
        $client = $this->getClient($user);
        $calendar = new Calendar($client);

        $event = new Event($eventData);

        return $calendar->events->insert('primary', $event);
    }

    public function getEvent(User $user, string $eventId): Event
    {
        $client = $this->getClient($user);
        $calendar = new Calendar($client);

        return $calendar->events->get('primary', $eventId);
    }

    public function updateEvent(User $user, string $eventId, Event $event): Event
    {
        $client = $this->getClient($user);
        $calendar = new Calendar($client);

        return $calendar->events->update('primary', $eventId, $event);
    }

    public function deleteEvent(User $user, string $eventId): void
    {
        $client = $this->getClient($user);
        $calendar = new Calendar($client);

        $calendar->events->delete('primary', $eventId);
    }
}

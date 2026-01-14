<?php

namespace App\Repositories\Clients;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

class ClientRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll($clientId)
    {
        return Client::with('quota')
            ->withCount([
                'employees',
                'employees as active_employees_count' => function ($query) {
                    $query->where('is_active', true);
                },
                'employees as inactive_employees_count' => function ($query) {
                    $query->where('is_active', false);
                },
            ])
            ->find($clientId);
    }

    public function getAllEmployees($clientId)
    {
        return User::where('client_id', $clientId)->pluck('id')->toArray();
    }

    /**
     * Get only active employee IDs for quota usage
     */
    public function getActiveEmployeeIds($clientId)
    {
        return User::where('client_id', $clientId)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Get only inactive employee IDs for history/audit
     */
    public function getInactiveEmployeeIds($clientId)
    {
        return User::where('client_id', $clientId)
            ->where('is_active', false)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Count engaged employees in a given period
     *
     * Engaged = memiliki minimal 1 appointment dengan status
     * 'confirmed', 'progress', atau 'completed'
     */
    public function countEngagedEmployees(array $employeeIds, Carbon $startDate, Carbon $endDate): int
    {
        if (empty($employeeIds)) {
            return 0;
        }

        return Appointment::whereIn('user_id', $employeeIds)
            ->whereIn('status', ['confirmed', 'progress', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');
    }
}

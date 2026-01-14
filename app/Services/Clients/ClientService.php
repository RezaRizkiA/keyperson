<?php

namespace App\Services\Clients;

use App\Repositories\Clients\ClientRepository;
use Carbon\Carbon;

class ClientService
{
    protected $clientRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getDashboardData($clientId)
    {
        $client = $this->clientRepository->getAll($clientId);
        $employeeIds = $this->clientRepository->getAllEmployees($clientId);

        // Calculate quota utilization using dynamic plan limit
        $quotaBalance = $client->quota?->balance ?? 0;
        $maxQuota = $client->quota?->getPlanLimit() ?? 5000000; // Default to starter plan
        $quotaPercentage = $maxQuota > 0 ? min(100, round(($quotaBalance / $maxQuota) * 100)) : 0;

        // Determine quota status
        $quotaStatus = $this->getQuotaStatus($quotaPercentage);

        // Calculate engagement stats
        $engagementStats = $this->getEngagementStats($clientId);

        return [
            'company_name' => $client->company_name,
            'employee_count_category' => $client->employee_count,
            'employee_registered' => $client->employees_count,
            'employee_active' => $client->active_employees_count,
            'employee_inactive' => $client->inactive_employees_count,
            // Quota data
            'quota_balance' => $quotaBalance,
            'quota_max' => $maxQuota,
            'quota_plan' => $client->quota?->plan ?? 'starter',
            'quota_percentage' => $quotaPercentage,
            'quota_status' => $quotaStatus['status'],
            'quota_message' => $quotaStatus['message'],
            'quota_action' => $quotaStatus['action'],
            // Engagement data
            'engagement_percentage' => $engagementStats['engagement_percentage'],
            'engagement_change' => $engagementStats['engagement_change'],
            'engaged_employees' => $engagementStats['engaged_employees'],
            'total_active_employees' => $engagementStats['total_active_employees'],
        ];
    }

    /**
     * Calculate employee engagement statistics
     * 
     * Business logic: Menghitung persentase karyawan aktif yang
     * memiliki minimal 1 appointment (confirmed/progress/completed)
     * di bulan ini, dan perbandingan dengan bulan lalu.
     * 
     * @param int $clientId
     * @return array
     */
    private function getEngagementStats($clientId): array
    {
        // Get active employee IDs dari repository
        $activeEmployeeIds = $this->clientRepository->getActiveEmployeeIds($clientId);
        $totalActiveEmployees = count($activeEmployeeIds);

        // Jika tidak ada karyawan aktif, return 0
        if ($totalActiveEmployees === 0) {
            return [
                'engaged_employees' => 0,
                'total_active_employees' => 0,
                'engagement_percentage' => 0,
                'engagement_change' => 0,
            ];
        }

        // Define periode bulan ini
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();

        // Define periode bulan lalu
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Query engaged employees via repository
        $engagedThisMonth = $this->clientRepository->countEngagedEmployees(
            $activeEmployeeIds,
            $thisMonthStart,
            $thisMonthEnd
        );

        $engagedLastMonth = $this->clientRepository->countEngagedEmployees(
            $activeEmployeeIds,
            $lastMonthStart,
            $lastMonthEnd
        );

        // Calculate percentages (business logic)
        $thisMonthPercentage = round(($engagedThisMonth / $totalActiveEmployees) * 100);
        $lastMonthPercentage = round(($engagedLastMonth / $totalActiveEmployees) * 100);
        $change = $thisMonthPercentage - $lastMonthPercentage;

        return [
            'engaged_employees' => $engagedThisMonth,
            'total_active_employees' => $totalActiveEmployees,
            'engagement_percentage' => $thisMonthPercentage,
            'engagement_change' => $change,
        ];
    }

    private function getQuotaStatus(int $percentage): array
    {
        if ($percentage >= 50) {
            return [
                'status' => 'healthy',
                'message' => 'Kredit konsultasi dalam kondisi sehat',
                'action' => null,
            ];
        } elseif ($percentage >= 20) {
            return [
                'status' => 'warning',
                'message' => 'Kredit konsultasi mulai menipis',
                'action' => 'Pertimbangkan untuk top up.',
            ];
        } else {
            return [
                'status' => 'critical',
                'message' => 'Kredit konsultasi sangat rendah',
                'action' => 'Action Required: Isi ulang kuota Anda.',
            ];
        }
    }
}

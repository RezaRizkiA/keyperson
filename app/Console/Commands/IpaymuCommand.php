<?php
namespace App\Console\Commands;

use App\Helpers\IpaymuHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class IpaymuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ipaymu-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            listPaymentIpaymu();
            $this->info('Ipaymu payment methods updated successfully.');
        } catch (\Throwable $e) {
            $this->error('Failed to update Ipaymu payment methods: ' . $e->getMessage());
            Log::error('[IpaymuCommand] Error: ' . $e->getMessage());
        }
    }
}

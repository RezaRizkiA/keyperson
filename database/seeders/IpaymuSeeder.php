<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IpaymuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Running iPaymu Channel Sync from Helper...');
        listPaymentIpaymu();
    }
}

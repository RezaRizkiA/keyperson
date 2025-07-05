<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;


class CheckIpaymuConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ipaymu:check-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and displays the current iPaymu configuration values being used by the application.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('--- Checking iPaymu Configuration ---');

        // Force reading from the config file, which should get values from .env
        $va          = Config::get('ipaymu.va');
        $apiKey      = Config::get('ipaymu.api_key');
        $channelsUrl = Config::get('ipaymu.url.channels');

        if ($va) {
            $this->line("VA (Virtual Account): " . $va);
        } else {
            $this->error("VA (Virtual Account) is NOT SET or NULL!");
        }

        if ($apiKey) {
            $this->line("API Key: " . $apiKey);
        } else {
            $this->error("API Key is NOT SET or NULL!");
        }

        if ($channelsUrl) {
            $this->line("Channels URL: " . $channelsUrl);
        } else {
            $this->error("Channels URL is NOT SET or NULL!");
        }

        $this->info('-----------------------------------');
        $this->comment('If these values are incorrect or null, please check your .env file and run "php artisan config:clear".');

        return 0;
    }
}


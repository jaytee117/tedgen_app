<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\TwoGApi;

class Get2GReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get2-g-reports-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the machines readings via the 2g API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Get 2G Reports scheduling');
        TwoGApi::get2GToken();
    }
}

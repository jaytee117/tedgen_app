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
        TwoGApi::get2GToken();
        $givenDt = new \DateTime('now', new \DateTimeZone('Europe/Amsterdam'));
        $givenDt->setTimezone(new \DateTimeZone('UTC')); //convert to UTC (will be 1 hour behind GMT during summer months)
        $hour = $givenDt->format('H');
        $date = $givenDt->format('Y-m-d');
        Log::info('Running 2G API Job for ' . $hour . ':00 UTC');
        TwoGApi::getReadingsFromApi($date, $hour);
        
    }
}

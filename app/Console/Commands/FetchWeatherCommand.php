<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Site;
use App\Services\WeatherLookup;

class FetchWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-weather-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the weather info from Open Waether API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Get 2G Reports scheduling');
        $site_array = Site::where('lat')->get();
        $count = 1;
        foreach($site_array as $site):
            if($count % 50 == 0):
                sleep(61);
                $count = 1;
            endif;
            $weather = new WeatherLookup($site->lat, $site->lng);
            if ($weather->getStatus() == 200):
                $result = $weather->getResults();
                //eventually, we will save the data to its own model too.
                $site->current_temp = $result->main->temp;
                $site->weather_icon = $result->weather[0]->icon;
                $site->save();
            endif;
            $count++;
        endforeach;
    }
}

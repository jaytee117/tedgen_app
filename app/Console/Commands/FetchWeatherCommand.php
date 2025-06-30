<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Site;
use App\Models\WeatherReading;
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
        Log::info('Get Weather Reports scheduling');
        $site_array = Site::where('lat')->get();
        Log::info($site_array);
        $count = 1;
        foreach($site_array as $site):
            if($count % 50 == 0):
                sleep(61);
                $count = 1;
            endif;
            $weather = new WeatherLookup($site->lat, $site->lng);
            if ($weather->getStatus() == 200):
                $result = $weather->getResults();
                $db = new WeatherReading();
                $db->site_id = $site->id;
                $db->reading_date = date('Y-m-d H');
                $db->temp = $result->main->temp;
                $db->pressure = $result->main->pressure;
                $db->humidity = $result->main->humidity;
                $db->wind_speed = $result->wind->speed;
                $db->cloud = $result->clouds->all;
                $db->sunrise = date('Y-m-d H:i:s', $result->sys->sunrise);
                $db->sunset = date('Y-m-d H:i:s', $result->sys->sunset);
                $db->icon = $result->weather[0]->icon;
                $db->save();
                $site->current_temp = $result->main->temp;
                $site->weather_icon = $result->weather[0]->icon;
                $site->save();
            else:
                Log::error($weather->getStatus() . ' weather API status');
            endif;
            $count++;
        endforeach;
    }
}

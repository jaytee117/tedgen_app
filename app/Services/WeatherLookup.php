<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WeatherLookup
{
    private $endpoint = 'http://api.openweathermap.org/data/2.5/weather?units=metric&';
    private $results;
    private $lat;
    private $lng;
    private $key = config('custom.openweather_key');
    private $status;

    public function __construct($lat, $lng) {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->doLookup();
    }

    public function getResults() {
        return $this->results;
    }

    public function getStatus() {
        return $this->status;
    }

    private function doLookup() {
        $returnurl = $this->endpoint . 'lat=' . $this->lat . '&lon=' . $this->lng . '&APPID=' . $this->key;
        $ch1 = curl_init();
        if (!$ch1)
            Log::info("Couldn't initialize a cURL handle for the Weather Lookup");
        curl_setopt($ch1, CURLOPT_URL, $returnurl);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch1, CURLOPT_TIMEOUT, 1 * 60);
        $result = curl_exec($ch1);
        $this->results = json_decode($result);
        $this->status = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
        curl_close($ch1);
    }
}

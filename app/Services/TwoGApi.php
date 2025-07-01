<?php

namespace App\Services;

use App\Models\Credentials;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Installation;

class TwoGApi
{
    public static function get2GToken()
    {
        $now = time();
        $credentials = Credentials::where('provider', 1)->first();
        $expires = $credentials->expires;
        if ($expires > $now):
            Log::info('expires = ' . $credentials->expires);
            Log::info('now = ' . $now);
            Log::info('2g token still valid');
        else:
            $client_id = Config::get('services.2gapi.client_id');
            $client_secret = Config::get('services.2gapi.client_secret');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Config::get('services.2gapi.token_url'));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'client_credentials'
            ));
            $data = curl_exec($ch);
            $decodedData = json_decode($data);
            $decodedJWT = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $decodedData->access_token)[1]))));
            Credentials::where('provider', 1)->update([
                'access_token' => $decodedData->access_token,
                'expires' => $decodedJWT->exp
            ]);
            Log::info('New 2G Token saved to db');
        endif;
    }

    public static function getReadings($date, $hour)
    {
        $credentials = Credentials::where('provider', 1)->first();
        $request_headers = [
            'Authorization: Bearer ' . $credentials->access_token,
        ];
        $installs = Installation::where('logger_type', 4)->whereNotNull('ip_address')->get();
        foreach ($installs as $install) {
            $url = 'https://api.2-g.energy/idc/v2/assets/chp/' . $install->ip_address . '/reports?filter=reportDateTime%20gt%20%22' . $date . 'T' . $hour . ':00%22%20AND%20reportDateTime%20lt%20%22' . $date . 'T' . $hour . ':59%22%20&take=1&skip=0';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            $result = curl_exec($ch);
            $decoded = json_decode($result);
            $data = $decoded->data;
            $results = [];
            Log::info($data);
        }
    }
}

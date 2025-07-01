<?php

namespace App\Services;
use App\Models\Credentials;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TwoGApi
{
    public static function get2GToken() {
        $now = time();
        $credentials = Credentials::where('provider', 1)->first();
        $expires = $credentials->expires;
        if ($expires > $now):
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
}
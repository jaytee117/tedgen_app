<?php

namespace App\Services;

use App\Models\Credentials;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Installation;
use App\Models\DataLine;
use App\Models\MeterReading;
use App\Models\LastCount;

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
            //ip address is actually asset_id from 2g
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
            if (count($data)):
                Log::info('>>> ' . $install->asset_id . ' has sent readings.');
                TwoGApi::parse2GReadings($result['data'], $result['date'], $install->site_id);
            else:
                Log::info('No Data for this period recorded for ' . $install->asset_id);
            endif;
        }
    }

    public static function parse2GReadings($data, $date, $siteID)
    {
        $elec_contract = DataLine::where('site_id', $siteID)->where('contract_type', 2)->first();
        $elec_reading = MeterReading::where('reading_type', 2)->where('site_id', $siteID)->where('contract_id', $elec_contract->chp_contract_id)->where('reading_date', $date)->first();
        $gas_contract = DataLine::where('site_id', $siteID)->where('contract_type', 3)->first();
        $gas_reading = MeterReading::where('reading_type', 2)->where('site_id', $siteID)->where('contract_id', $gas_contract->chp_contract_id)->where('reading_date', $date)->first();
        $therm_contract = DataLine::where('site_id', $siteID)->where('contract_type', 1)->first();
        $therm_reading = MeterReading::where('reading_type', 2)->where('site_id', $siteID)->where('contract_id', $therm_contract->chp_contract_id)->where('reading_date', $date)->first();
        if ($elec_reading):
            TwoGApi::append2GReading(1, $elec_reading, $data, $siteID);
        else:
            TwoGApi::new2GReading(1, $data, $elec_contract, $siteID, $date);
        endif;
        if ($gas_reading):
            TwoGApi::append2GReading(2, $gas_reading, $data, $siteID);
        else:
            TwoGApi::new2GReading(2, $data, $gas_contract, $siteID, $date);
        endif;
        if ($therm_reading):
            TwoGApi::append2GReading(3, $therm_reading, $data, $siteID);
        else:
            TwoGApi::new2GReading(3, $data, $therm_contract, $siteID, $date);
        endif;
    }

    public static function append2GReading($type, $crm_reading, $data, $siteID)
    {
        $timeArray = MeterReading::hhTimeArray();
        $hh = json_decode($crm_reading->hh_data);
        $key = array_search($data[0]->time, $timeArray);
        $message = false;
        switch ($type) {
            case 1:
                $lastElec = LastCount::where('site_id', $siteID)->where('type', 2)->first();
                $hh[$key] = ($data[0]->ElecReading - $lastElec->last_reading);
                $lastElec->last_reading = $data[0]->ElecReading;
                $lastElec->save();
                break;
            case 2:
                $lastGas = LastCount::where('site_id', $siteID)->where('type', 3)->first();
                $hh[$key] = ($data[0]->GasReading - $lastGas->last_reading);
                $lastGas->last_reading = $data[0]->GasReading;
                $lastGas->save();
                break;
            case 3:
                $lastHeat = LastCount::where('site_id', $siteID)->where('type', 1)->first();
                $pulseReading = (($data[0]->HeatReading - $lastHeat->last_reading) * 70) / 100000;
                $lastHeat->last_reading = $data[0]->HeatReading;
                $lastHeat->save();
                $hh[$key] = $pulseReading;
                break;
        }
        if ($data[0]->state == 'fault'):
            $crm_reading->online_status = 3;
            $crm_reading->online = 0;
        else:
            $crm_reading->online_status = 0;
            $crm_reading->online = 1;
        endif;
        $crm_reading->total = array_sum($hh);
        $crm_reading->hh_data = json_encode($hh);
        $crm_reading->save();
    }

    public static function new2GReading($type, $data, $contract, $siteID, $date)
    {
        $timeArray = MeterReading::hhTimeArray();
        $hh = array_fill(0, 48, 0);
        foreach ($data as $line):
            $key = array_search($line->time, $timeArray);
            if (false !== $key):
                switch ($type) {
                    case 1:
                        $lastElec = LastCount::where('site_id', $siteID)->where('type', 2)->first();
                        $hh[$key] = $line->ElecReading - $lastElec->last_reading;
                        $lastElec->last_reading = $line->ElecReading;
                        $lastElec->save();
                        break;
                    case 2:
                        $lastGas = LastCount::where('site_id', $siteID)->where('type', 3)->first();
                        $hh[$key] = $line->GasReading - $lastGas->last_reading;
                        $lastGas->last_reading = $line->GasReading;
                        $lastGas->save();
                        break;
                    case 3:
                        $lastHeat = LastCount::where('site_id', $siteID)->where('type', 1)->first();
                        $pulseReading = (($line->HeatReading - $lastHeat->last_reading) * 70) / 100000;
                        $lastHeat->last_reading = $line->HeatReading;
                        $lastHeat->save();
                        $hh[$key] = $pulseReading;
                        break;
                }
            endif;
        endforeach;
        $elec_reading = new MeterReading();
        $elec_reading->site_id = $siteID;
        $elec_reading->contract_id = $contract->chp_contract_id;
        $elec_reading->reading_type = 2;
        $elec_reading->reading_date = $date;
        $elec_reading->meter_reference = $contract->meter_reference;
        $elec_reading->total = array_sum($hh);
        $elec_reading->hh_data = json_encode($hh);
        $elec_reading->save();
    }
}

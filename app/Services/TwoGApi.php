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
    public static function check2GToken()
    {
        $now = time();
        $credentials = Credentials::where('provider', 1)->first();
        $expires = $credentials->expires;
        if ($expires <= $now):
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

    public static function getReadingsFromApi($date, $hour)
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
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlResult = json_decode($result);
            curl_close($ch);
            if ($status == 200):
                TwoGApi::createDataFrom2G($curlResult, $install, $status);
            else:
                Log::info('Unable to gather reports for ' . $install->asset_id . '- report status = ' . $status);
            endif;
        }
    }

    public static function createDataFrom2G($curlResult, $install, $status)
    {
        $api_results = [];
        $data = $curlResult->data;
        foreach ($data as $line):
            $datetime = new \DateTime($line->reportDateTime);
            $date = $datetime->format('Y-m-d');
            $time = $datetime->format('H:00');
            $single = new \stdClass();
            $single->date = $date;
            $single->time = $time;
            $single->ElecReading = $line->counterPowerProduced;
            $single->HeatReading = $line->counterHeatQuantityPlantHeatCircuit;
            $single->GasReading = $line->counterConsumptionGasType1;
            $single->activeGas = $line->consumptionGasType1;
            $single->activePower = $line->activePower;
            $single->state = $line->state;
            $single->reportId = $line->reportId;
            $api_results[] = $single;
        endforeach;
        if (count($data) > 0):
            Log::info($install->asset_id . ' has sent readings.');
            TwoGApi::parse2GReadings($api_results, $date, $install);
        else:
            Log::info('No Data for this period recorded for ' . $install->asset_id);
            Log::info('Status Code:'  . $status);
            $install->machine_status = 4;
            $install->save();
        endif;
    }

    public static function parse2GReadings($api_results, $date, $install)
    {
        if ($api_results[0]->state == 'fault'):
            $install->machine_status = 3;
        else:
            $install->machine_status = 0;
        endif;
        $install->save();
        $elec_dataline = DataLine::where('installation_id', $install->id)->where('data_line_type', 2)->first();
        $elec_reading = MeterReading::where('reading_type', 2)->where('dataline_id', $elec_dataline->id)->where('reading_date', $date)->first();
        $gas_dataline = DataLine::where('installation_id', $install->id)->where('data_line_type', 3)->first();
        $gas_reading = MeterReading::where('reading_type', 2)->where('dataline_id', $gas_dataline->id)->where('reading_date', $date)->first();
        $therm_dataline = DataLine::where('installation_id', $install->id)->where('data_line_type', 1)->first();
        $therm_reading = MeterReading::where('reading_type', 2)->where('dataline_id', $therm_dataline->id)->where('reading_date', $date)->first();
        if ($elec_reading):
            TwoGApi::append2GReading(1, $elec_reading, $api_results, $install);
        else:
            TwoGApi::new2GReading(1, $api_results, $elec_dataline, $install, $date);
        endif;
        if ($gas_reading):
            TwoGApi::append2GReading(2, $gas_reading, $api_results, $install);
        else:
            TwoGApi::new2GReading(2, $api_results, $gas_dataline, $install, $date,);
        endif;
        if ($therm_reading):
            TwoGApi::append2GReading(3, $therm_reading, $api_results, $install);
        else:
            TwoGApi::new2GReading(3, $api_results, $therm_dataline, $install, $date);
        endif;
    }

    public static function append2GReading($type, $reading, $api_results, $install)
    {
        $timeArray = MeterReading::hhTimeArray();
        $hh = json_decode($reading->hh_data);
        $key = array_search($api_results[0]->time, $timeArray);
        //last reading
        switch ($type) {
            case 1:
                $lastElec = LastCount::where('installation_id', $install->id)->where('type', 2)->first();
                $hh[$key] = ($api_results[0]->ElecReading - $lastElec->last_reading);
                $lastElec->last_reading = $api_results[0]->ElecReading;
                $lastElec->save();
                break;
            case 2:
                $lastGas = LastCount::where('installation_id', $install->id)->where('type', 3)->first();
                $hh[$key] = ($api_results[0]->GasReading - $lastGas->last_reading);
                $lastGas->last_reading = $api_results[0]->GasReading;
                $lastGas->save();
                break;
            case 3:
                $lastHeat = LastCount::where('installation_id', $install->id)->where('type', 1)->first();
                $hh[$key] = (($api_results[0]->HeatReading - $lastHeat->last_reading) * 70) / 100000;
                $lastHeat->last_reading = $api_results[0]->HeatReading;
                $lastHeat->save();
                break;
        }
        $reading->total = array_sum($hh);
        $reading->hh_data = json_encode($hh);
        $reading->save();
    }

    public static function new2GReading($type, $api_results, $dataline, $install, $date)
    {
        $timeArray = MeterReading::hhTimeArray();
        $hh = array_fill(0, 48, 0);
        foreach ($api_results as $line):
            $key = array_search($line->time, $timeArray);
            if (false !== $key):
                switch ($type) {
                    case 1:
                        $lastElec = LastCount::where('installation_id', $install->id)->where('type', 2)->first();
                        $hh[$key] = $line->ElecReading - $lastElec->last_reading;
                        $lastElec->last_reading = $line->ElecReading;
                        $lastElec->save();
                        break;
                    case 2:
                        $lastGas = LastCount::where('installation_id', $install->id)->where('type', 3)->first();
                        $hh[$key] = $line->GasReading - $lastGas->last_reading;
                        $lastGas->last_reading = $line->GasReading;
                        $lastGas->save();
                        break;
                    case 3:
                        $lastHeat = LastCount::where('installation_id', $install->id)->where('type', 1)->first();
                        $hh[$key] = (($line->HeatReading - $lastHeat->last_reading) * 70) / 100000;
                        $lastHeat->last_reading = $line->HeatReading;
                        $lastHeat->save();
                        break;
                }
            endif;
        endforeach;
        $reading = new MeterReading();
        $reading->site_id = $install->site_id;
        $reading->installation_id = $install->id;
        $reading->dataline_id = $dataline->id;
        $reading->reading_type = 2;
        $reading->reading_date = $date;
        $reading->meter_reference = $dataline->line_reference;
        $reading->total = array_sum($hh);
        $reading->hh_data = json_encode($hh);
        $reading->save();
    }
}

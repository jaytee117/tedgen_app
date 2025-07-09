<?php

namespace App\Actions;

use App\Models\DataLine;
use App\Models\Installation;
use App\Models\LastCount;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Log;

class InstallationAction
{
    public static function calcTedGenRate($rate, $ccl, $teddiscount, $cclDiscount)
    {
        $cdec = $cclDiscount / 100;
        $ccldiscount = $cdec * $ccl;
        $cclDiscounted = $ccl - $ccldiscount;
        $discountdec = $teddiscount / 100;
        $totalRate = $rate + $cclDiscounted;
        $result = $totalRate * (1 - $discountdec);
        return number_format($result, 6);
    }

    public static function storeNewRates($validated)
    {
        $validated['tedgen_elec_day'] = InstallationAction::calcTedGenRate($validated['elec_day_rate'], $validated['elec_ccl_rate'], $validated['tedgen_discount'], $validated['elec_ccl_discount']);
        $validated['tedgen_elec_night'] = InstallationAction::calcTedGenRate($validated['elec_night_rate'], $validated['elec_ccl_rate'], $validated['tedgen_discount'], $validated['elec_ccl_discount']);
        $validated['tedgen_gas_heating'] = InstallationAction::calcTedGenRate($validated['gas_rate'], $validated['gas_ccl_rate'], $validated['tedgen_discount'], $validated['elec_ccl_discount']);
        return $validated;
    }

    public static function createDataLines(Installation $installation)

    {

        for ($x = 1; $x <= 3; $x++) {
            DataLine::create([
                'installation_id' => $installation->id,
                'data_line_type' => $x,
                'line_reference' => Dataline::$_data_line_type[$x]
            ]);
        }
        return;
    }

    public static function createLastCounts(Installation $installation)
    {
        for ($x = 1; $x <= 3; $x++) {
            LastCount::create([
                'installation_id' => $installation->id,
                'type' => $x,
                'last_reading' => 0
            ]);
        }
        return;
    }

    public static function getYearsReadings($installation_id)
    {
        $newdate = date("Y-m-d", strtotime("-11 months"));
        $results = [];
        $rates = Installation::where('id', $installation_id)->first();
        $heatingContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 1)->first();
        $elecContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 2)->first();
        $gasContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 3)->first();
        if ($heatingContract && $elecContract && $gasContract):
            $elecresult = MeterReading::selectRaw('year(reading_date) year, month(reading_date) month,  sum(total) total')
                ->where('dataline_id', $elecContract->id)
                ->whereBetween('reading_date', [$newdate, date("Y-m-d")])
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->get();
            $counter = 0;
            foreach ($elecresult as $month):
                $dateObj = \DateTime::createFromFormat('!m', $month->month);
                $monthName = $dateObj->format('F'); // March
                $heatGenerated = MeterReading::where('dataline_id', $heatingContract->id)->whereMonth('reading_date', $month->month)->whereYear('reading_date', $month->year)->sum('total');
                if ($rates->machine_type == 1):
                    $heatingKwh = $heatGenerated;
                else:
                    $heatingKwh = InstallationAction::convertHeatingToKwh($heatGenerated, $rates->boiler_efficiency);
                endif;

                $gasConsumed = MeterReading::where('dataline_id', $gasContract->id)->whereMonth('reading_date', $month->month)->whereYear('reading_date', $month->year)->sum('total');
                $elecGen = MeterReading::where('dataline_id', $elecContract->id)->whereMonth('reading_date', $month->month)->whereYear('reading_date', $month->year)->sum('total');
                $gaskWh = (($rates->calorific_value * $rates->conversion_factor) / 3.6) * $gasConsumed;
                $results[] = [$monthName . ' ' . $month->year, (int) $heatingKwh, (int) $elecGen, (int) $gaskWh, 0, 0];
                $counter++;
            endforeach;
            return $results;
        else:
            return $results;
        endif;
    }

    public static function getMonthsReadings($installation_id, $month)
    {
        $rates = Installation::where('id', $installation_id)->first();
        $heatingContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 1)->first();
        $elecContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 2)->first();
        $gasContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 3)->first();
        if ($heatingContract && $elecContract && $gasContract):
            $usage = [];
            $timestamp = strtotime($month);
            $startdate = date('Y-m-d', $timestamp);
            $now = new \DateTime($startdate);
            $enddate = $now->modify('first day of next month')->format('Y-m-d');
            $heatingreadings = MeterReading::where('dataline_id', $heatingContract->id)->where('reading_date', '>=', $startdate)->where('reading_date', '<', $enddate)->orderBy('reading_date')->get()->toArray();
            $gasreadings = MeterReading::where('dataline_id', $gasContract->id)->where('reading_date', '>=', $startdate)->where('reading_date', '<', $enddate)->orderBy('reading_date')->get()->toArray();
            $elecreadings = MeterReading::where('dataline_id', $elecContract->id)->where('reading_date', '>=', $startdate)->where('reading_date', '<', $enddate)->orderBy('reading_date')->get();
            $counter = 0;
            foreach ($elecreadings as $reading):
                $date = new \DateTime($reading->reading_date);
                if (isset($heatingreadings[$counter])):
                    if ($rates->machine_type == 1):
                        $heatingKwh = $heatingreadings[$counter]['total'];
                    else:
                        $heatingKwh = InstallationAction::convertHeatingToKwh($heatingreadings[$counter]['total'], $rates->boiler_efficiency);
                    endif;
                else:
                    $heatingKwh = 0;
                endif;
                if (isset($gasreadings[$counter])):
                    $gaskWh = (($rates->calorific_value * $rates->conversion_factor) / 3.6) * $gasreadings[$counter]['total'];
                else:
                    $gaskWh = 0;
                endif;
                $usage[] = [$date->format('d-m-Y'), (int) $heatingKwh, (int) $reading->total, (int) $gaskWh, 0, 0];
                $counter++;
            endforeach;
            return  $usage;
        else:
            return [];
        endif;
    }

    public static function getHHReadings($installation_id, $readingdate)
    {
        $rates = Installation::where('id', $installation_id)->first();
        $heatingContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 1)->first();
        $elecContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 2)->first();
        $gasContract = DataLine::where('installation_id', $installation_id)->where('data_line_type', 3)->first();
        $date = \DateTime::createFromFormat('d-m-Y', $readingdate)->format('Y-m-d');
        $heatingreadings = MeterReading::where('dataline_id', $heatingContract->id)->where('reading_date', $date)->first();
        $gasreadings = MeterReading::where('dataline_id', $gasContract->id)->where('reading_date', $date)->first();
        $elecreadings = MeterReading::where('dataline_id', $elecContract->id)->where('reading_date', $date)->first();
        $hhdata_heating = json_decode($heatingreadings->hh_data);
        $hhdata_elec = json_decode($elecreadings->hh_data);
        $hhdata_gas = json_decode($gasreadings->hh_data);
        $hhdata_elecinput = false;
        $hhdata_gasinput = false;
        $start = "00:00";
        $end = "23:30";
        $tStart = strtotime($start);
        $tEnd = strtotime($end);
        $tNow = $tStart;
        $i = 0;
        while ($tNow <= $tEnd) {
            if ($rates->machine_type == 1):
                $heatingKwh = $hhdata_heating[$i];
            else:
                $heatingKwh = InstallationAction::convertHeatingToKwh($hhdata_heating[$i], $rates->boiler_efficiency);
            endif;
            $gaskWh = (($rates->calorific_value * $rates->conversion_factor) / 3.6) * $hhdata_gas[$i];
            if ($hhdata_elecinput):
                $elecinput = $hhdata_elecinput[$i];
            else:
                $elecinput = 0;
            endif;

            $hh[] = [date("H:i", $tNow), $heatingKwh, $hhdata_elec[$i], $gaskWh, 0, 0];
            $tNow = strtotime('+30 minutes', $tNow);
            $i++;
        }
        //this part determines if its a 2g API, strip out the half hour reads as it only reads on the hour
        if ($rates->logger_type == 4):
            foreach ($hh as $key => $one) {
                if (strpos($one[0], ':30') > 0)
                    unset($hh[$key]);
            }
            $data = array_values($hh);
        else:
            $data = $hh;
        endif;
        return $data;
    }

    public static function getDashboardStats(){
        $todayDT = new \DateTime();
        $today = $todayDT->format("Y-m-d");
        $todayminusone = $todayDT->add(\DateInterval::createFromDateString('yesterday'));
        $yesterday = $todayminusone->format("Y-m-d");
        $month = date("m");
        $year = date("Y");

        $heatDataLines = DataLine::where('data_line_type', 1)->pluck('id');
        $heatToday = MeterReading::whereIn('dataline_id', $heatDataLines)->where('reading_date', $today)->sum('total');
        $heatTodaykWh = InstallationAction::convertHeatingToKwh($heatToday, 70);
        $heatYesterday = MeterReading::whereIn('dataline_id', $heatDataLines)->where('reading_date', $yesterday)->sum('total');
        $heatYesterdaykWh = InstallationAction::convertHeatingToKwh($heatYesterday, 70);
        $heatThisMonth = MeterReading::whereIn('dataline_id', $heatDataLines)->whereMonth('reading_date', $month)->sum('total');
        $heatThisMonthkWh = InstallationAction::convertHeatingToKwh($heatThisMonth, 70);
        $heatThisYear = MeterReading::whereIn('dataline_id', $heatDataLines)->whereYear('reading_date', $year)->sum('total');
        $heatThisYearkWh = InstallationAction::convertHeatingToKwh($heatThisYear, 70);

        $elecDataLines = DataLine::where('data_line_type', 2)->pluck('id');
        $elecToday = MeterReading::whereIn('dataline_id', $elecDataLines)->where('reading_date', $today)->sum('total');        
        $elecYesterday = MeterReading::whereIn('dataline_id', $elecDataLines)->where('reading_date', $yesterday)->sum('total');       
        $elecThisMonth = MeterReading::whereIn('dataline_id', $elecDataLines)->whereMonth('reading_date', $month)->sum('total');        
        $elecThisYear = MeterReading::whereIn('dataline_id', $elecDataLines)->whereYear('reading_date', $year)->sum('total');

        $gasDataLines = DataLine::where('data_line_type', 3)->pluck('id');
        $gasToday = MeterReading::whereIn('dataline_id', $gasDataLines)->where('reading_date', $today)->sum('total');
        $gasTodaykWh = ((39.5 * 1.03) / 3.6) * $gasToday;       
        $gasYesterday = MeterReading::whereIn('dataline_id', $gasDataLines)->where('reading_date', $yesterday)->sum('total');
        $gasYesterdaykWh = ((39.5 * 1.03) / 3.6) * $gasYesterday;      
        $gasThisMonth = MeterReading::whereIn('dataline_id', $gasDataLines)->whereMonth('reading_date', $month)->sum('total');
        $gasThisMonthkWh = ((39.5 * 1.03) / 3.6) * $gasThisMonth;        
        $gasThisYear = MeterReading::whereIn('dataline_id', $gasDataLines)->whereYear('reading_date', $year)->sum('total');
        $gasThisYearkWh = ((39.5 * 1.03) / 3.6) * $gasThisYear;
        
        $stats = new \stdClass();
        $stats->elec_today = number_format($elecToday,0);
        $stats->elec_yesterday = number_format($elecYesterday,0);
        $stats->elec_month = number_format($elecThisMonth,0);
        $stats->elec_year = number_format($elecThisYear,0);
        $stats->gas_today = number_format($gasTodaykWh,0);
        $stats->gas_yesterday = number_format($gasYesterdaykWh,0);
        $stats->gas_month = number_format($gasThisMonthkWh,0);
        $stats->gas_year = number_format($gasThisYearkWh,0);
        $stats->heat_today = number_format($heatTodaykWh,0);
        $stats->heat_yesterday = number_format($heatYesterdaykWh,0);
        $stats->heat_month = number_format($heatThisMonthkWh,0);
        $stats->heat_year = number_format($heatThisYearkWh,0);
        return $stats;

    }

    public static function convertHeatingToKwh($reading, $boilerEfficiency)
    {
        if ($boilerEfficiency > 0):
            $calc = ($reading * 1000) / $boilerEfficiency;
            $result = $calc * 100;
            return $result;
        else:
            return 0;
        endif;
    }
}

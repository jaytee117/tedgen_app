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
        $heatingContract = DataLine::where('installation_id', $installation_id)->where('contract_type', 1)->first();
        $elecContract = DataLine::where('installation_id', $installation_id)->where('contract_type', 2)->first();
        $gasContract = DataLine::where('installation_id', $installation_id)->where('contract_type', 3)->first();
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

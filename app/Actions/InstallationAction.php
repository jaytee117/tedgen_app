<?php

namespace App\Actions;

use App\Models\DataLine;
use App\Models\Installation;
use App\Models\LastCount;

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
}

<?php

namespace App\Actions;

class InstallationAction
{
    public static function calcTedGenRate($rate, $ccl, $teddiscount, $cclDiscount) {
        $cdec = $cclDiscount/100;
        $ccldiscount = $cdec * $ccl;
        $cclDiscounted = $ccl - $ccldiscount;
        $discountdec = $teddiscount / 100;
        $totalRate = $rate + $cclDiscounted;
        $result = $totalRate * (1 - $discountdec);
        return number_format($result, 6);
    }

    public static function storeNewRates($validated){
        $validated['tedgen_elec_day'] = InstallationAction::calcTedGenRate($validated['elec_day_rate'], $validated['elec_ccl_rate'], $validated['tedgen_discount'], $validated['elec_ccl_discount']);
        $validated['tedgen_elec_night'] = InstallationAction::calcTedGenRate($validated['elec_night_rate'], $validated['elec_ccl_rate'], $validated['tedgen_discount'], $validated['elec_ccl_discount']);
        $validated['tedgen_gas_heating'] = InstallationAction::calcTedGenRate($validated['gas_rate'], $validated['gas_ccl_rate'], $validated['tedgen_discount'], $validated['elec_ccl_discount']);
        return $validated;
    }
}
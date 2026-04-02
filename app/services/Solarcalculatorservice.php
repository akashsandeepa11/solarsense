<?php

require_once __DIR__ . '/../config/config.php';

class SolarCalculatorService
{
    // Temperature coefficient lookup by module type code
    private const TEMP_COEFFICIENTS = [
        0 => TEMP_COEFF_STANDARD,   // Standard silicon
        1 => TEMP_COEFF_PREMIUM,    // Premium silicon
        2 => TEMP_COEFF_THIN_FILM,  // Thin film (CdTe / CIGS)
    ];
 
    /**
     * Produce a corrected monthly + annual generation estimate.
     *
     * @param array $pvwatts      Output from PVWattsService::fetchSystemPerformance()
     * @param array $weatherMonthly  Monthly aggregates from OpenMeteoService::aggregateMonthly()
     * @param float $systemCapacityKw  Panel system capacity in kW
     * @param int   $moduleType   0=Standard, 1=Premium, 2=Thin film
     *
     * @return array{
     *     monthly: array,
     *     ac_annual_kwh: float,
     *     ac_annual_pvwatts_kwh: float,
     *     capacity_factor: float,
     *     method: string
     * }
     */
    public function calculate(
        array $pvwatts,
        array $weatherMonthly,
        float $systemCapacityKw,
        int   $moduleType = 0
    ): array {
        $gamma = self::TEMP_COEFFICIENTS[$moduleType] ?? TEMP_COEFF_STANDARD;
 
        $monthly        = [];
        $acAnnualKwh    = 0.0;
        $monthNames     = ['Jan','Feb','Mar','Apr','May','Jun',
                           'Jul','Aug','Sep','Oct','Nov','Dec'];
 
        for ($m = 1; $m <= 12; $m++) {
            $idx = $m - 1; // PVWatts arrays are 0-indexed
 
            // ── Step 1: Derive monthly Performance Ratio from PVWatts ────────
            // PR_m = AC_pvwatts_m / (capacity × POA_m)
            // POA (Plane-of-Array) irradiance from PVWatts is in kWh/m²
            $pvAC  = $pvwatts['ac_monthly_kwh'][$idx]  ?? 0.0;
            $pvPOA = $pvwatts['poa_monthly_kwh'][$idx] ?? 0.0;
 
            // Avoid division by zero in months with no sun
            $denominator = $systemCapacityKw * $pvPOA;
            $pr = ($denominator > 0) ? ($pvAC / $denominator) : 0.0;
 
            // ── Step 2: Get actual GTI from Open-Meteo for this month ────────
            $w = $weatherMonthly[$m] ?? [];
            $gtiKwh = $w['gti_kwh_m2'] ?? 0.0; // kWh/m² on the panel surface
 
            // ── Step 3: Calculate actual average cell temperature ────────────
            // Using simplified Faiman model:
            // T_cell ≈ T_air + (NOCT − 20) / 800 × G_poa (W/m²)
            // Open-Meteo gives monthly GTI in kWh/m²; we need average W/m²
            // kWh/m² / hours_in_month × 1000 = average W/m²
            $hoursInMonth    = $this->hoursInMonth($m);
            $avgGtiWm2       = ($gtiKwh * 1000) / max($hoursInMonth, 1);
            $tAir            = $w['temp_avg_c'] ?? 25.0;
            $tCellActual     = $tAir + ((DEFAULT_NOCT - 20) / 800) * $avgGtiWm2;
 
            // Wind cooling: each 1 m/s above 1 m/s reduces cell temp by ~1.5°C
            // Open-Meteo wind is in km/h — convert to m/s
            $windMs   = ($w['wind_avg_kmh'] ?? 0) / 3.6;
            $windCorr = max(0, ($windMs - 1.0) * 1.5); // cooling correction
            $tCellActual -= $windCorr;
 
            // ── Step 4: PVWatts modelled cell temperature ────────────────────
            $tCellPvwatts = $pvwatts['tcell_monthly_c'][$idx] ?? 25.0;
 
            // ── Step 5: Temperature derating correction factor ───────────────
            // η_pvwatts already baked in its cell temp; we correct for the
            // difference between PVWatts' cell temp and our actual cell temp
            $deltaTCell   = $tCellActual - $tCellPvwatts;
            $etaTempAdj   = 1.0 + ($gamma * $deltaTCell);
 
            // Clamp to sensible bounds — avoids runaway on bad data
            $etaTempAdj = max(0.5, min(1.2, $etaTempAdj));
 
            // ── Step 6: Corrected AC estimate for this month ─────────────────
            // AC_est = GTI_actual × capacity × PR_pvwatts × η_temp_adj
            $acEstKwh = $gtiKwh * $systemCapacityKw * $pr * $etaTempAdj;
            $acEstKwh = max(0.0, round($acEstKwh, 2));
 
            $acAnnualKwh += $acEstKwh;
 
            $monthly[$m] = [
                'month'              => $monthNames[$m - 1],
                'month_num'          => $m,
                // Irradiance (kWh/m²)
                'gti_kwh_m2'         => round($gtiKwh, 3),
                'ghi_kwh_m2'         => round($w['ghi_kwh_m2'] ?? 0, 3),
                // Temperatures
                't_air_avg_c'        => round($tAir, 2),
                't_cell_actual_c'    => round($tCellActual, 2),
                't_cell_pvwatts_c'   => round($tCellPvwatts, 2),
                'wind_avg_ms'        => round($windMs, 2),
                // Performance parameters
                'performance_ratio'  => round($pr, 4),
                'eta_temp_adj'       => round($etaTempAdj, 4),
                // Energy output
                'ac_pvwatts_kwh'     => round($pvAC, 2),   // TMY baseline
                'ac_estimated_kwh'   => $acEstKwh,          // corrected estimate
            ];
        }
 
        $acAnnualKwh = round($acAnnualKwh, 2);
 
        // Capacity factor = annual output / (capacity × 8760 hours)
        $capacityFactor = round(
            $acAnnualKwh / max(1, $systemCapacityKw * 8760),
            4
        );
 
        return [
            'monthly'               => $monthly,
            'ac_annual_kwh'         => $acAnnualKwh,
            'ac_annual_pvwatts_kwh' => round($pvwatts['ac_annual_kwh'], 2),
            'capacity_factor'       => $capacityFactor,
            'method'                => 'PVWatts-PR × Open-Meteo-GTI × temp-correction',
        ];
    }
 
    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────
 
    // Returns total hours in a given month (using a non-leap-year reference)
    private function hoursInMonth(int $month): int
    {
        $days = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        return $days[$month] * 24;
    }
}
?>
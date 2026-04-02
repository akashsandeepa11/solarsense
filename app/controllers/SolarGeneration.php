<?php

require_once __DIR__ . '/../config/config.php';

// controllers/SolarGeneration.php
// Orchestrates the full solar generation estimation workflow:
//   1. Load system record from DB
//   2. Resolve district → lat/lon
//   3. Call PVWatts API (system performance parameters)
//   4. Call Open-Meteo API (real historical weather + irradiance)
//   5. Run SolarCalculatorService (blended estimate)
//   6. Persist result to DB
//   7. Return structured result

require_once __DIR__ . '/../models/M_Solarsystem.php';
require_once __DIR__ . '/../models/M_Solargeneration.php';
require_once __DIR__ . '/../services/PVWattsservice.php';
require_once __DIR__ . '/../services/Openmeteoservice.php';
require_once __DIR__ . '/../services/Solarcalculatorservice.php';

class SolarGeneration extends Controller
{
    private $systemModel;
    private $generationModel;
    private $pvwatts;
    private $openMeteo;
    private $calculator;

    public function __construct()
    {
        $this->systemModel     = $this->model('M_Solarsystem');
        $this->generationModel = $this->model('M_Solargeneration');
        $this->pvwatts         = $this->model('PVWattsservice');
        $this->openMeteo       = $this->model('Openmeteoservice');
        $this->calculator      = $this->model('Solarcalculatorservice');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Main entry point: estimate generation for a system over a date range.
    // Call from your route/front controller.
    //
    // $systemId  — ID from solar_systems table
    // $dateFrom  — 'YYYY-MM-DD'  (e.g. first day of previous year)
    // $dateTo    — 'YYYY-MM-DD'  (e.g. last day of previous year)
    //
    // Returns array with 'success', 'data' or 'error' keys.
    // ─────────────────────────────────────────────────────────────────────────
    public function estimateGeneration(
        int    $systemId,
        string $dateFrom,
        string $dateTo
    ): array {
        try {
            // ── 1. Load system from DB ────────────────────────────────────────
            $system = $this->systemModel->findById($systemId);
            if (!$system) {
                return $this->error("System ID $systemId not found.");
            }

            // ── 2. Resolve district to coordinates ───────────────────────────
            $district = $system['district'];
            if (!isset(DISTRICT_COORDINATES[$district])) {
                return $this->error("Unknown district: $district");
            }
            $lat = DISTRICT_COORDINATES[$district]['lat'];
            $lon = DISTRICT_COORDINATES[$district]['lon'];

            // ── 3. Read system parameters (with defaults for unset fields) ───
            $capacity = (float)$system['capacity'];
            $tilt     = (float)$system['tilt'];
            $azimuth  = (float)$system['azimuth'];
            $moduleType = (int)($system['module_type']  ?? DEFAULT_MODULE_TYPE);
            $arrayType  = (int)($system['array_type']   ?? DEFAULT_ARRAY_TYPE);
            $losses     = (float)($system['losses_pct'] ?? DEFAULT_LOSSES);
            $dcAcRatio  = (float)($system['dc_ac_ratio']  ?? DEFAULT_DC_AC_RATIO);
            $invEff     = (float)($system['inv_eff_pct']  ?? DEFAULT_INV_EFF);

            // ── 4. Call PVWatts V8 API ────────────────────────────────────────
            $pvwattsResult = $this->pvwatts->fetchSystemPerformance(
                lat:            $lat,
                lon:            $lon,
                systemCapacity: $capacity,
                tilt:           $tilt,
                azimuth:        $azimuth,
                moduleType:     $moduleType,
                arrayType:      $arrayType,
                losses:         $losses,
                dcAcRatio:      $dcAcRatio,
                invEff:         $invEff
            );

            // ── 5. Call Open-Meteo historical API ────────────────────────────
            $weatherData = $this->openMeteo->fetchHistoricalWeather(
                lat:      $lat,
                lon:      $lon,
                dateFrom: $dateFrom,
                dateTo:   $dateTo,
                tilt:     $tilt,
                azimuth:  $azimuth
            );
            $weatherMonthly = $this->openMeteo->aggregateMonthly($weatherData['hourly']);

            // ── 6. Run blended calculation ────────────────────────────────────
            $result = $this->calculator->calculate(
                pvwatts:          $pvwattsResult,
                weatherMonthly:   $weatherMonthly,
                systemCapacityKw: $capacity,
                moduleType:       $moduleType
            );

            // ── 7. Persist result to DB ───────────────────────────────────────
            $estimateId = $this->generationModel->save([
                'system_id'      => $systemId,
                'date_from'      => $dateFrom,
                'date_to'        => $dateTo,
                'ac_annual_kwh'  => $result['ac_annual_kwh'],
                'ac_monthly_kwh' => array_column($result['monthly'], 'ac_estimated_kwh'),
                'capacity_factor'=> $result['capacity_factor'],
                'weather_source' => 'Open-Meteo ERA5 + PVWatts V8 TMY',
                'pvwatts_raw'    => $pvwattsResult,
            ]);

            // ── 8. Build and return response ──────────────────────────────────
            return [
                'success'      => true,
                'estimate_id'  => $estimateId,
                'system_id'    => $systemId,
                'district'     => $district,
                'lat'          => $lat,
                'lon'          => $lon,
                'date_from'    => $dateFrom,
                'date_to'      => $dateTo,
                'system'       => [
                    'capacity_kw' => $capacity,
                    'tilt_deg'    => $tilt,
                    'azimuth_deg' => $azimuth,
                    'module_type' => $moduleType,
                    'array_type'  => $arrayType,
                    'losses_pct'  => $losses,
                ],
                'result'       => $result,
                'weather_meta' => $weatherData['meta'],
                'pvwatts_station' => $pvwattsResult['station_info'],
            ];

        } catch (RuntimeException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            // Catch unexpected errors without leaking stack traces to clients
            error_log('SolarGeneration: ' . $e->getMessage());
            return $this->error('An unexpected error occurred. Please try again.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Retrieve the latest saved estimate for a system (no API calls)
    // ─────────────────────────────────────────────────────────────────────────
    public function getLatestEstimate(int $systemId): array
    {
        $system = $this->systemModel->findById($systemId);
        if (!$system) {
            return $this->error("System ID $systemId not found.");
        }

        $estimate = $this->generationModel->findLatestBySystem($systemId);
        if (!$estimate) {
            return $this->error("No estimate found for system $systemId.");
        }

        return ['success' => true, 'system' => $system, 'estimate' => $estimate];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Convenience: run estimation for all systems in DB (e.g. cron job)
    // ─────────────────────────────────────────────────────────────────────────
    public function estimateAllSystems(string $dateFrom, string $dateTo): array
    {
        $systems = $this->systemModel->findAll();
        $results = [];

        foreach ($systems as $system) {
            $results[] = $this->estimateGeneration(
                (int)$system['system_id'],
                $dateFrom,
                $dateTo
            );
            // Respect API rate limits — PVWatts allows 1000 req/hour
            usleep(200_000); // 200 ms between calls
        }

        return $results;
    }

    private function error(string $message): array
    {
        return ['success' => false, 'error' => $message];
    }
}
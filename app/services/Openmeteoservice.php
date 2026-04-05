<?php
require_once __DIR__ . '/../config/config.php';
class OpenMeteoService
{
    private string $baseUrl;
 
    public function __construct()
    {
        $this->baseUrl = OPENMETEO_API_URL;
    }
 
    /**
     * Fetch hourly historical weather + irradiance for a location and date range.
     *
     * @param float  $lat       Latitude (decimal degrees)
     * @param float  $lon       Longitude (decimal degrees)
     * @param string $dateFrom  Start date 'YYYY-MM-DD'
     * @param string $dateTo    End date   'YYYY-MM-DD'
     * @param float  $tilt      Panel tilt in degrees (0 = horizontal, 90 = vertical)
     * @param float  $azimuth   Panel azimuth in Open-Meteo convention
     *                          (0 = south, -90 = east, 90 = west, ±180 = north)
     *
     * @return array{
     *     hourly: array{
     *         time: string[],
     *         temperature_2m: float[],
     *         wind_speed_10m: float[],
     *         shortwave_radiation: float[],
     *         direct_normal_irradiance: float[],
     *         diffuse_radiation: float[],
     *         global_tilted_irradiance: float[]
     *     },
     *     meta: array{ lat: float, lon: float, elevation: float, timezone: string }
     * }
     *
     * @throws RuntimeException on HTTP or API error
     */
    public function fetchHistoricalWeather(
        float  $lat,
        float  $lon,
        string $dateFrom,
        string $dateTo,
        float  $tilt    = 0.0,
        float  $azimuth = 0.0
    ): array {
        // Build query parameters
        $params = [
            'latitude'   => $lat,
            'longitude'  => $lon,
            'start_date' => $dateFrom,
            'end_date'   => $dateTo,
            'timezone'   => 'Asia/Colombo',
            'hourly'     => implode(',', [
                'temperature_2m',
                'wind_speed_10m',
                'shortwave_radiation',
                'direct_normal_irradiance',
                'diffuse_radiation',
                'global_tilted_irradiance',
            ]),
            // Pass tilt and azimuth so Open-Meteo computes GTI for us
            'tilt'    => $tilt,
            'azimuth' => $this->convertAzimuthToOpenMeteo($azimuth),
        ];
 
        $url = $this->baseUrl . '?' . http_build_query($params);
        $raw = $this->httpGet($url);
        $data = json_decode($raw, true);
 
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Open-Meteo: invalid JSON response');
        }
        if (!empty($data['error'])) {
            throw new RuntimeException('Open-Meteo error: ' . ($data['reason'] ?? 'unknown'));
        }
 
        return [
            'hourly' => $data['hourly'],
            'meta'   => [
                'lat'       => $data['latitude'],
                'lon'       => $data['longitude'],
                'elevation' => $data['elevation'],
                'timezone'  => $data['timezone'],
            ],
        ];
    }
 
    /**
     * Aggregate hourly data into monthly totals and averages.
     * Returns an array of 12 elements (months 1–12) with:
     *   ghi_sum, dni_sum, dhi_sum, gti_sum  (Wh/m²)
     *   temp_avg, wind_avg
     *
     * @param array $hourly  The 'hourly' sub-array from fetchHistoricalWeather()
     */
    public function aggregateMonthly(array $hourly): array
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = [
                'ghi_sum'  => 0.0,
                'gti_sum'  => 0.0,
                'dni_sum'  => 0.0,
                'dhi_sum'  => 0.0,
                'temp_sum' => 0.0,
                'wind_sum' => 0.0,
                'count'    => 0,
            ];
        }
 
        foreach ($hourly['time'] as $i => $timestamp) {
            $month = (int) date('n', strtotime($timestamp));
 
            // Hourly values are W/m² averaged over the preceding hour → Wh/m²
            $months[$month]['ghi_sum']  += (float)($hourly['shortwave_radiation'][$i]        ?? 0);
            $months[$month]['gti_sum']  += (float)($hourly['global_tilted_irradiance'][$i]   ?? 0);
            $months[$month]['dni_sum']  += (float)($hourly['direct_normal_irradiance'][$i]   ?? 0);
            $months[$month]['dhi_sum']  += (float)($hourly['diffuse_radiation'][$i]          ?? 0);
            $months[$month]['temp_sum'] += (float)($hourly['temperature_2m'][$i]             ?? 0);
            $months[$month]['wind_sum'] += (float)($hourly['wind_speed_10m'][$i]             ?? 0);
            $months[$month]['count']++;
        }
 
        // Convert sums to averages where needed; keep irradiance as totals (kWh/m²)
        foreach ($months as $m => &$d) {
            $n = max($d['count'], 1);
            $d['ghi_kwh_m2']  = round($d['ghi_sum']  / 1000, 3); // Wh → kWh
            $d['gti_kwh_m2']  = round($d['gti_sum']  / 1000, 3);
            $d['dni_kwh_m2']  = round($d['dni_sum']  / 1000, 3);
            $d['dhi_kwh_m2']  = round($d['dhi_sum']  / 1000, 3);
            $d['temp_avg_c']  = round($d['temp_sum']  / $n,  2);
            $d['wind_avg_kmh'] = round($d['wind_sum'] / $n,  2);
        }
 
        return $months;
    }
 
    // -------------------------------------------------------------------------
    // PVWatts uses meteorological azimuth (0=N, 90=E, 180=S, 270=W).
    // Open-Meteo uses (0=S, -90=E, 90=W, ±180=N).
    // This converts PVWatts / standard compass azimuth → Open-Meteo convention.
    // -------------------------------------------------------------------------
    private function convertAzimuthToOpenMeteo(float $compassAzimuth): float
    {
        // Map: 180° compass (south) → 0° Open-Meteo
        $om = $compassAzimuth - 180.0;
        // Normalise to (-180, 180]
        if ($om > 180)  $om -= 360;
        if ($om <= -180) $om += 360;
        return $om;
    }
 
    // Simple cURL GET with timeout and error handling
    private function httpGet(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'SolarEstimator/1.0',
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
 
        if ($response === false || $curlError) {
            throw new RuntimeException("Open-Meteo cURL error: $curlError");
        }
        if ($httpCode !== 200) {
            throw new RuntimeException("Open-Meteo HTTP $httpCode: $response");
        }
        return $response;
    }
}
?>
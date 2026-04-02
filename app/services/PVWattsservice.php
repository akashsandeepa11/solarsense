<?php
require_once __DIR__ . '/../config/config.php';
class PVWattsService
{
    private string $apiUrl;
    private string $apiKey;
 
    public function __construct()
    {
        $this->apiUrl = PVWATTS_API_URL;
        $this->apiKey = PVWATTS_API_KEY;
    }
 
    /**
     * Fetch PVWatts V8 simulation results for a system.
     *
     * @param float  $lat            Latitude
     * @param float  $lon            Longitude
     * @param float  $systemCapacity System size in kW (DC)
     * @param float  $tilt           Panel tilt in degrees
     * @param float  $azimuth        Panel azimuth — meteorological convention
     *                               (0=N, 90=E, 180=S, 270=W)
     * @param int    $moduleType     0=Standard, 1=Premium, 2=Thin film
     * @param int    $arrayType      1=Fixed roof, 2=Fixed ground, 3=1-axis, 4=1-axis backtrack, 5=2-axis
     * @param float  $losses         Total system losses %
     * @param float  $dcAcRatio      DC to AC ratio
     * @param float  $invEff         Inverter efficiency %
     * @param float  $albedo         Ground reflectivity
     *
     * @return array  Parsed PVWatts output
     * @throws RuntimeException
     */
    public function fetchSystemPerformance(
        float $lat,
        float $lon,
        float $systemCapacity,
        float $tilt,
        float $azimuth,
        int   $moduleType  = DEFAULT_MODULE_TYPE,
        int   $arrayType   = DEFAULT_ARRAY_TYPE,
        float $losses      = DEFAULT_LOSSES,
        float $dcAcRatio   = DEFAULT_DC_AC_RATIO,
        float $invEff      = DEFAULT_INV_EFF,
        float $albedo      = DEFAULT_ALBEDO
    ): array {
        $params = [
            'api_key'         => $this->apiKey,
            'lat'             => $lat,
            'lon'             => $lon,
            'system_capacity' => $systemCapacity,
            'tilt'            => $tilt,
            'azimuth'         => $azimuth,
            'module_type'     => $moduleType,
            'array_type'      => $arrayType,
            'losses'          => $losses,
            'dc_ac_ratio'     => $dcAcRatio,
            'inv_eff'         => $invEff,
            'albedo'          => $albedo,
            'dataset'         => 'nsrdb',
            'timeframe'       => 'monthly',
        ];
 
        $url = $this->apiUrl . '?' . http_build_query($params);
        $raw = $this->httpGet($url);
        $data = json_decode($raw, true);
 
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('PVWatts: invalid JSON response');
        }
        if (!empty($data['errors'])) {
            throw new RuntimeException('PVWatts errors: ' . implode('; ', $data['errors']));
        }
 
        return $this->parseOutputs($data);
    }
 
    /**
     * Extract and structure the outputs we need from the raw PVWatts response.
     */
    private function parseOutputs(array $data): array
    {
        $out = $data['outputs'] ?? [];
 
        // ac_monthly, poa_monthly, tcell_monthly, dc_monthly are arrays of 12
        // solrad_monthly is array of 12 (avg daily kWh/m²)
        return [
            'ac_annual_kwh'     => round($out['ac_annual'] ?? 0, 2),
            'ac_monthly_kwh'    => array_map(fn($v) => round($v, 2), $out['ac_monthly']    ?? array_fill(0, 12, 0)),
            'dc_monthly_kwh'    => array_map(fn($v) => round($v, 2), $out['dc_monthly']    ?? array_fill(0, 12, 0)),
            'poa_monthly_kwh'   => array_map(fn($v) => round($v, 2), $out['poa_monthly']   ?? array_fill(0, 12, 0)),
            'tcell_monthly_c'   => array_map(fn($v) => round($v, 2), $out['tcell_monthly'] ?? array_fill(0, 12, 0)),
            'solrad_monthly'    => array_map(fn($v) => round($v, 3), $out['solrad_monthly'] ?? array_fill(0, 12, 0)),
            // Derived: performance ratio = AC / (capacity_kW * poa_irradiance)
            // Stored for blending with Open-Meteo data
            'capacity_factor'   => isset($out['capacity_factor']) ? round($out['capacity_factor'], 4) : null,
            'station_info'      => $data['station_info']  ?? [],
            'inputs_echo'       => $data['inputs']        ?? [],
        ];
    }
 
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
            throw new RuntimeException("PVWatts cURL error: $curlError");
        }
        if ($httpCode !== 200) {
            throw new RuntimeException("PVWatts HTTP $httpCode: $response");
        }
        return $response;
    }
}
?>
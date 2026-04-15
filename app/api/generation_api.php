<?php

function getSolarGenerationByMonth(
    int    $month,
    float  $systemCapacity,
    int    $moduleType,
    float  $losses,
    int    $arrayType,
    float  $tilt,
    float  $azimuth,
    float  $lat,
    float  $lon,
    string $apiKey
): array {
    $params = http_build_query([
        'api_key'         => $apiKey,
        'lat'             => $lat,
        'lon'             => $lon,
        'system_capacity' => $systemCapacity,
        'module_type'     => $moduleType,
        'losses'          => $losses,
        'array_type'      => $arrayType,
        'tilt'            => $tilt,
        'azimuth'         => $azimuth,
        'timeframe'       => 'monthly',
    ]);

    // echo '<script> console.log("aaahhhhhh.. fuck uuuu...") </script>';

    $url = "https://developer.nrel.gov/api/pvwatts/v8.json?{$params}";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($curlErr) {
        return ['success' => false, 'message' => "cURL error: {$curlErr}"];
    }

    $json = json_decode($response, true);

    if ($httpCode !== 200) {
        return [
            'success' => false,
            'message' => "API returned HTTP {$httpCode}",
            'errors'  => $json['errors'] ?? null,
        ];
    }

    if (!isset($json['outputs']['ac_monthly'])) {
        return [
            'success' => false,
            'message' => 'Unexpected API response structure — ac_monthly missing.',
            'errors'  => $json['errors'] ?? null,
        ];
    }

    // ac_monthly is a 0-indexed array [Jan=0 … Dec=11]
    $monthIndex    = $month - 1;
    $generationKwh = (float) $json['outputs']['ac_monthly'][$monthIndex];

    return [
        'success'        => true,
        'month_number'   => $month,
        'generation_kwh' => $generationKwh,
    ];
}

<?php

function getSolarGenerationByMonth(
    int $month,
    float $systemCapacity = 5,
    int $moduleType = 0,
    float $losses = 14,
    int $arrayType = 1,
    float $tilt = 10,
    float $azimuth = 180,
    float $lat = 6.9271,
    float $lon = 79.8612,
    string $apiKey = '2C1Fb2aAX2bAXNwZgmTvBq279kMUR0XMpXj07GgS'
): array {
    
    // Validate month
    if ($month < 1 || $month > 12) {
        return [
            'success' => false,
            'message' => 'Invalid month number. Use 1 to 12.'
        ];
    }

    $params = [
        'api_key' => $apiKey,
        'system_capacity' => $systemCapacity,
        'module_type' => $moduleType,
        'losses' => $losses,
        'array_type' => $arrayType,
        'tilt' => $tilt,
        'azimuth' => $azimuth,
        'lat' => $lat,
        'lon' => $lon,
        'timeframe' => 'monthly'
    ];

    $url = 'https://developer.nrel.gov/api/pvwatts/v8.json?' . http_build_query($params);

    $response = @file_get_contents($url);

    if ($response === false) {
        return [
            'success' => false,
            'message' => 'API request failed'
        ];
    }

    $data = json_decode($response, true);

    if (!$data) {
        return [
            'success' => false,
            'message' => 'Invalid JSON response'
        ];
    }

    if (!empty($data['errors'])) {
        return [
            'success' => false,
            'message' => 'API returned errors',
            'errors' => $data['errors']
        ];
    }

    if (!isset($data['outputs']['ac_monthly']) || !is_array($data['outputs']['ac_monthly'])) {
        return [
            'success' => false,
            'message' => 'Monthly generation data not found'
        ];
    }

    
    $generation = $data['outputs']['ac_monthly'][$month - 1] ?? null;

    if ($generation === null) {
        return [
            'success' => false,
            'message' => 'Generation value not found for month'
        ];
    }

    return [
        'success' => true,
        'month_number' => $month,
        'generation_kwh' => $generation,
        'full_response' => $data
    ];
}

?>

<!-- 
$result = getSolarGenerationByMonth(
                month: JAN, 
                systemCapacity: 5,
                moduleType: 0,
                losses: 14,
                arrayType: 1,
                tilt: 10,
                azimuth: 180,
                lat: 6.9271,
                lon: 79.8612,
                apiKey: NREL_API_KEY
            );

            if ($result['success']) {
                echo "Month: " . $result['month_number'] . PHP_EOL;
                echo "Generation: " . round($result['generation_kwh'], 2) . " kWh" . PHP_EOL;
            } else {
                echo "Error: " . $result['message'] . PHP_EOL;
            
                if (isset($result['errors'])) {
                    print_r($result['errors']);
                }
            } -->
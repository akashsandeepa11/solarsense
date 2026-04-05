<?php

function getDailySolarForecast(
    float $lat,
    float $lon
): array {
    $params = http_build_query([
        'latitude'  => $lat,
        'longitude' => $lon,
        'current'   => 'temperature_2m,weather_code',
        'daily'     => 'temperature_2m_max,temperature_2m_min,weather_code,shortwave_radiation_sum',
        'timezone'  => 'auto',
        'forecast_days' => 1
    ]);

    $url = "https://api.open-meteo.com/v1/forecast?{$params}";

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
        return [
            'success' => false,
            'message' => "cURL error: {$curlErr}"
        ];
    }

    $json = json_decode($response, true);

    if ($httpCode !== 200) {
        return [
            'success' => false,
            'message' => "API returned HTTP {$httpCode}",
        ];
    }

    if (
        !isset($json['current']['temperature_2m']) ||
        !isset($json['current']['weather_code']) ||
        !isset($json['daily']['temperature_2m_max'][0]) ||
        !isset($json['daily']['temperature_2m_min'][0]) ||
        !isset($json['daily']['shortwave_radiation_sum'][0])
    ) {
        return [
            'success' => false,
            'message' => 'Unexpected API response structure.'
        ];
    }

    $weatherCode = (int) $json['current']['weather_code'];

    return [
        'success' => true,
        'temperature' => round((float) $json['current']['temperature_2m']),
        'condition' => mapWeatherCodeToText($weatherCode),
        'weather_code' => $weatherCode,
        'temp_max' => (float) $json['daily']['temperature_2m_max'][0],
        'temp_min' => (float) $json['daily']['temperature_2m_min'][0],
        'solar_radiation_mj' => (float) $json['daily']['shortwave_radiation_sum'][0],
        'date' => $json['daily']['time'][0] ?? null,
    ];
}

function mapWeatherCodeToText(int $code): string
{
    return match ($code) {
        0 => 'Clear sky',
        1 => 'Mainly clear',
        2 => 'Partly cloudy',
        3 => 'Overcast',
        45, 48 => 'Fog',
        51, 53, 55 => 'Drizzle',
        56, 57 => 'Freezing drizzle',
        61, 63, 65 => 'Rain',
        66, 67 => 'Freezing rain',
        71, 73, 75 => 'Snow fall',
        77 => 'Snow grains',
        80, 81, 82 => 'Rain showers',
        85, 86 => 'Snow showers',
        95 => 'Thunderstorm',
        96, 99 => 'Thunderstorm with hail',
        default => 'Unknown'
    };
}
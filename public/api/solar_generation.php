<?php
// public/api/solar_generation.php
// HTTP entry point for the solar generation estimation API.
// Called from your frontend via fetch/AJAX.
//
// Endpoints:
//   POST /api/solar_generation.php
//        { "action": "estimate", "system_id": 1, "date_from": "2024-01-01", "date_to": "2024-12-31" }
//
//   POST /api/solar_generation.php
//        { "action": "latest", "system_id": 1 }
//
//   POST /api/solar_generation.php
//        { "action": "estimate_all", "date_from": "2024-01-01", "date_to": "2024-12-31" }

declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');

// Bootstrap: constants and autoloads
require_once '../../app/config/config.php';
require_once '../../app/controllers/SolarGeneration.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Parse JSON body
$body = json_decode(file_get_contents('php://input'), true);
if (!$body || json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON body']);
    exit;
}

$action = $body['action'] ?? '';

// --- Basic input validation helpers -----------------------------------------
function requireInt(array $body, string $key): int
{
    if (!isset($body[$key]) || !is_numeric($body[$key])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => "Missing or invalid field: $key"]);
        exit;
    }
    return (int)$body[$key];
}

function requireDate(array $body, string $key): string
{
    $val = $body[$key] ?? '';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => "Invalid date for $key (expected YYYY-MM-DD)"]);
        exit;
    }
    return $val;
}

$controller = new SolarGeneration();

switch ($action) {

    case 'estimate':
        $systemId = requireInt($body, 'system_id');
        $dateFrom = requireDate($body, 'date_from');
        $dateTo   = requireDate($body, 'date_to');

        if ($dateFrom > $dateTo) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'date_from must be before date_to']);
            exit;
        }

        $result = $controller->estimateGeneration($systemId, $dateFrom, $dateTo);
        http_response_code($result['success'] ? 200 : 400);
        echo json_encode($result, JSON_PRETTY_PRINT);
        break;

    case 'latest':
        $systemId = requireInt($body, 'system_id');
        $result = $controller->getLatestEstimate($systemId);
        http_response_code($result['success'] ? 200 : 404);
        echo json_encode($result, JSON_PRETTY_PRINT);
        break;

    case 'estimate_all':
        // Protect this endpoint — add your own auth check here
        $dateFrom = requireDate($body, 'date_from');
        $dateTo   = requireDate($body, 'date_to');
        $result = $controller->estimateAllSystems($dateFrom, $dateTo);
        echo json_encode(['success' => true, 'results' => $result], JSON_PRETTY_PRINT);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Unknown action: $action"]);
}
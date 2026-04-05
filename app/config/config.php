<?php
// Load the .env file
loadEnv(dirname(dirname(__DIR__)) . '/.env');

// Database configuration using env variables
define("DB_HOST", getenv('DB_HOST'));
define("DB_USER", getenv('DB_USER'));
define("DB_PASSWORD", getenv('DB_PASSWORD'));
define("DB_NAME", getenv('DB_NAME'));
// APPROOT 
define("APPROOT", dirname(dirname(__FILE__)));
// URLROOT - dynamic for both local and hosted environments
// Check if using HTTPS (also check for proxy headers used by Render/cloud platforms)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];

// Check if running locally or on hosted server
if (strpos($domain, 'localhost') !== false || strpos($domain, '127.0.0.1') !== false) {
    // Local environment (XAMPP) - points to project root
    define('URLROOT', $protocol . $domain . '/solarsense');
} else {
    // Hosted environment (Render) - document root is /public, but URLs don't need /public
    // Since all assets are referenced as URLROOT/public/css/..., we point URLROOT to parent
    define('URLROOT', $protocol . $domain);
}
// WEBSITE NAME 
define("SITENAME", "SolarSense");

// Default PVWatts system parameters — override per-owner where known
define('DEFAULT_MODULE_TYPE', 0);    // 0 = Standard, 1 = Premium, 2 = Thin film
define('DEFAULT_ARRAY_TYPE', 1);    // 1 = Fixed (roof mount), 2 = 1-axis tracking, 4 = 2-axis
define('DEFAULT_LOSSES', 14.0); // % total system losses (wiring, soiling, mismatch, etc.)
define('DEFAULT_DC_AC_RATIO', 1.2);  // Typical residential/commercial ratio
define('DEFAULT_INV_EFF', 96.0); // Inverter efficiency %
define('DEFAULT_ALBEDO', 0.2);  // Ground reflectivity (grass ~0.2, concrete ~0.3)

// Temperature coefficient for standard silicon panels (% per °C above 25°C)
// Standard: -0.004, Premium: -0.0035, Thin-film: -0.002
define('TEMP_COEFF_STANDARD', -0.004);
define('TEMP_COEFF_PREMIUM', -0.0035);
define('TEMP_COEFF_THIN_FILM', -0.002);

// NOCT = Nominal Operating Cell Temperature (°C) — used in cell temp calculation
// T_cell = T_air + (NOCT - 20) / 800 * G_poa
define('DEFAULT_NOCT', 45.0);

// Sri Lanka districts with approximate centroid coordinates
// Source: Department of Census and Statistics Sri Lanka
const DISTRICT_COORDINATES = [
    'Colombo' => ['lat' => 6.9271, 'lon' => 79.8612],
    'Gampaha' => ['lat' => 7.0840, 'lon' => 80.0098],
    'Kalutara' => ['lat' => 6.5854, 'lon' => 79.9607],
    'Kandy' => ['lat' => 7.2906, 'lon' => 80.6337],
    'Matale' => ['lat' => 7.4675, 'lon' => 80.6234],
    'Nuwara Eliya' => ['lat' => 6.9497, 'lon' => 80.7891],
    'Galle' => ['lat' => 6.0535, 'lon' => 80.2210],
    'Matara' => ['lat' => 5.9549, 'lon' => 80.5550],
    'Hambantota' => ['lat' => 6.1241, 'lon' => 81.1185],
    'Jaffna' => ['lat' => 9.6615, 'lon' => 80.0255],
    'Kilinochchi' => ['lat' => 9.3803, 'lon' => 80.3770],
    'Mannar' => ['lat' => 8.9810, 'lon' => 79.9044],
    'Vavuniya' => ['lat' => 8.7514, 'lon' => 80.4971],
    'Mullaitivu' => ['lat' => 9.2671, 'lon' => 80.8128],
    'Batticaloa' => ['lat' => 7.7170, 'lon' => 81.7000],
    'Ampara' => ['lat' => 7.2980, 'lon' => 81.6724],
    'Trincomalee' => ['lat' => 8.5874, 'lon' => 81.2152],
    'Kurunegala' => ['lat' => 7.4818, 'lon' => 80.3609],
    'Puttalam' => ['lat' => 8.0408, 'lon' => 79.8394],
    'Anuradhapura' => ['lat' => 8.3114, 'lon' => 80.4037],
    'Polonnaruwa' => ['lat' => 7.9403, 'lon' => 81.0188],
    'Badulla' => ['lat' => 6.9934, 'lon' => 81.0550],
    'Monaragala' => ['lat' => 6.8728, 'lon' => 81.3507],
    'Ratnapura' => ['lat' => 6.6828, 'lon' => 80.3992],
    'Kegalle' => ['lat' => 7.2513, 'lon' => 80.3464],
];

    // PayHere payment gateway
    define("PAYHERE_MERCHANT_ID",     getenv('PAYHERE_MERCHANT_ID'));
    define("PAYHERE_MERCHANT_SECRET", getenv('PAYHERE_MERCHANT_SECRET'));
    define("PAYHERE_SANDBOX",         getenv('PAYHERE_SANDBOX') === 'true');
    
    // NREL API Key
    define("NREL_API_KEY", getenv('NREL_API_KEY'));
?>

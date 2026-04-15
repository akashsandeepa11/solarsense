<?php
// --- User Role Constants ---

define('ROLE_SUPER_ADMIN', 'SuperAdmin');
define('ROLE_INSTALLER_ADMIN', 'InstallerAdmin');
define('ROLE_OPERATION_MANAGER', 'OperationManager');
define('ROLE_INVENTORY_MANAGER', 'InventoryManager');
define('ROLE_SERVICE_AGENT', 'ServiceAgent');
define('ROLE_HOMEOWNER', 'Homeowner');


// Months
define('JAN', 1);
define('FEB', 2);
define('MAR', 3);
define('APR', 4);
define('MAY', 5);
define('JUN', 6);
define('JUL', 7);
define('AUG', 8);
define('SEP', 9);
define('OCT', 10);
define('NOV', 11);
define('DEC', 12);

// DISTRICTS
define('COLOMBO', 1);
define('GAMPAHA', 2);
define('KALUTARA', 3);
define('KANDY', 4);
define('MATALE', 5);
define('NUWARA_ELIYA', 6);
define('GALLE', 7);
define('MATARA', 8);
define('HAMBANTOTA', 9);
define('JAFFNA', 10);
define('KILINOCHCHI', 11);
define('MANNAR', 12);
define('MULLAITIVU', 13);
define('VAVUNIYA', 14);
define('BATTICALOA', 15);
define('AMPARA', 16);
define('TRINCOMALEE', 17);
define('ANURADHAPURA', 18);
define('POLONNARUWA', 19);
define('BADULLA', 20);
define('MONARAGALA', 21);
define('KEGALLE', 22);
define('RATNAPURA', 23);
define('KURUNEGALA', 24);
define('PUTTALAM', 25);

define('DISTRICTS', [
    'Colombo'      => ['lat' => 6.9271, 'lon' => 79.8612],
    'Gampaha'      => ['lat' => 7.0917, 'lon' => 79.9990],
    'Kalutara'     => ['lat' => 6.5854, 'lon' => 79.9607],
    'Kandy'        => ['lat' => 7.2906, 'lon' => 80.6337],
    'Matale'       => ['lat' => 7.4675, 'lon' => 80.6234],
    'Nuwara Eliya' => ['lat' => 6.9497, 'lon' => 80.7891],
    'Galle'        => ['lat' => 6.0535, 'lon' => 80.2210],
    'Matara'       => ['lat' => 5.9549, 'lon' => 80.5550],
    'Hambantota'   => ['lat' => 6.1241, 'lon' => 81.1185],
    'Jaffna'       => ['lat' => 9.6615, 'lon' => 80.0255],
    'Kilinochchi'  => ['lat' => 9.3803, 'lon' => 80.3770],
    'Mannar'       => ['lat' => 8.9770, 'lon' => 79.9040],
    'Mullaitivu'   => ['lat' => 9.2671, 'lon' => 80.8142],
    'Vavuniya'     => ['lat' => 8.7514, 'lon' => 80.4971],
    'Batticaloa'   => ['lat' => 7.7102, 'lon' => 81.6924],
    'Ampara'       => ['lat' => 7.2975, 'lon' => 81.6820],
    'Trincomalee'  => ['lat' => 8.5874, 'lon' => 81.2152],
    'Anuradhapura' => ['lat' => 8.3114, 'lon' => 80.4037],
    'Polonnaruwa'  => ['lat' => 7.9403, 'lon' => 81.0188],
    'Badulla'      => ['lat' => 6.9895, 'lon' => 81.0550],
    'Monaragala'   => ['lat' => 6.8728, 'lon' => 81.3507],
    'Kegalle'      => ['lat' => 7.2513, 'lon' => 80.3464],
    'Ratnapura'    => ['lat' => 6.7056, 'lon' => 80.3847],
    'Kurunegala'   => ['lat' => 7.4863, 'lon' => 80.3623],
    'Puttalam'     => ['lat' => 8.0362, 'lon' => 79.8283],
]);


// --- System Health Performance Thresholds ---
// These define the percentage bands for classifying solar performance vs expected generation.
define('HEALTH_EXCELLENT_THRESHOLD', 95); // >= 90% → Excellent
define('HEALTH_GOOD_THRESHOLD',      90); // >= 75% and < 90% → Good
define('HEALTH_WARNING_THRESHOLD',   80); // >= 50% and < 75% → Warning
                                          // <  50%           → Critical

define('HEALTH_STATUS_EXCELLENT', 'Excellent');
define('HEALTH_STATUS_GOOD',      'Good');
define('HEALTH_STATUS_WARNING',   'Warning');
define('HEALTH_STATUS_CRITICAL',  'Critical');

// --- System Health Status Colors (rgba for Chart.js bars, hex for UI badges) ---
define('HEALTH_COLOR_EXCELLENT', 'rgba(34, 197, 94, 0.75)');   // green
define('HEALTH_COLOR_GOOD',      'rgba(56, 189, 248, 0.75)');   // blue
define('HEALTH_COLOR_WARNING',   'rgba(245, 158, 11, 0.75)');   // amber
define('HEALTH_COLOR_CRITICAL',  'rgba(239, 68, 68, 0.75)');    // red


?>


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
        // Local environment (XAMPP)
        define('URLROOT', $protocol . $domain . '/solarsense');
    } else {
        // Hosted environment (production)
        define('URLROOT', $protocol . $domain);
    }
    // WEBSITE NAME 
    define("SITENAME", "SolarSense"); 
?>
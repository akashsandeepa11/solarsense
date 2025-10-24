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
    // URLROOT 
    define("URLROOT", "http://localhost/solarsense"); 
    // WEBSITE NAME 
    define("SITENAME", "SolarSense"); 
?>
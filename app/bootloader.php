<?php
    // Define base app directory
    define('APP_DIR', __DIR__);
    
    // Load Helpers
    require_once APP_DIR . "/helpers/EnvLoader_Helper.php";
    require_once APP_DIR . '/helpers/URL_Helper.php';
    require_once APP_DIR . '/helpers/Session_Helper.php';
    require_once APP_DIR . '/helpers/Toast_Helper.php';
    require_once APP_DIR . '/helpers/Avatar_Helper.php';

    // Load Configarations
    require_once APP_DIR . "/config/config.php";
    // Load Constatnts (MUST be loaded before RouteProtection_Helper)
    require_once APP_DIR . '/config/constants.php';
    
    // Load Route Protection Helper (after constants are defined)
    require_once APP_DIR . '/helpers/RouteProtection_Helper.php';
    
    // Load libraries
    require_once APP_DIR . "/libraries/Controller.php";
    require_once APP_DIR . "/libraries/Core.php";
    require_once APP_DIR . "/libraries/Database.php";
?>
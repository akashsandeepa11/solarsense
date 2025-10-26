<?php

    function redirect($page){
        $url = URLROOT.'/'.$page;
        error_log("DEBUG: Redirecting to: " . $url);
        header('location: '.$url);
        exit(); // Force exit after redirect
    }

?>
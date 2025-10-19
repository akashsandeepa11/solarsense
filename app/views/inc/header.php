<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    
    <!-- Local fonts are loaded via CSS @font-face; no external font CDNs used -->
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Your Custom CSS (loads last to override Tailwind if needed) -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/main.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/toast_styles.css">

    
</head>
<body>
    <!-- Display Toast Messages -->
    <?php displayToast(); ?>
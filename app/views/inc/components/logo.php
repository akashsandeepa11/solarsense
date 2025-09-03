<?php
/**
 * Logo Component
 * Displays the SolarSense logo with text
 * 
 * Usage: require APPROOT . '/views/inc/components/logo.php';
 */
?>
<div class="text-center py-3 mb-3">
    <a href="<?php echo URLROOT; ?>" class="d-flex align-center justify-center text-decoration-none hover:no-underline">
        <img src="<?php echo URLROOT; ?>/public/img/logo.png" width="40" height="40" alt="Logo" class="rounded-lg">
        <span class="sidebar-logo ml-2">SolarSense</span>
    </a>
</div>

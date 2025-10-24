<?php
/**
 * Logo Component
 * Displays the SolarSense logo with text
 * 
 * Usage: require APPROOT . '/views/inc/components/logo.php';
 */
?>
<?php
$componentLogoVariant = isset($logoVariant) ? strtolower($logoVariant) : 'default';
$logoImage = $componentLogoVariant === 'white' ? 'solasense_logo_white.png' : 'logo.png';
$textClasses = 'sidebar-logo ml-2 logo-component__text' . ($componentLogoVariant === 'white' ? ' text-white' : '');

if (isset($logoWrapperClass) && trim($logoWrapperClass) !== '') {
    $wrapperClass = trim($logoWrapperClass);
} else {
    $wrapperClass = 'text-center py-3 mb-3 logo-component';
}

if (isset($logoLinkClass) && trim($logoLinkClass) !== '') {
    $linkClass = trim($logoLinkClass);
} else {
    $linkClass = 'd-flex align-center justify-center text-decoration-none hover:no-underline logo-component__link';
}
?>
<div class="<?php echo $wrapperClass; ?>">
    <a href="<?php echo URLROOT; ?>" class="<?php echo $linkClass; ?>">
        <img src="<?php echo URLROOT; ?>/public/img/<?php echo $logoImage; ?>" width="40" height="40" alt="Logo" class="rounded-lg">
        <span class="<?php echo $textClasses; ?>">SolarSense</span>
    </a>
</div>

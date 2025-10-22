<?php
/**
 * Avatar Helper
 * 
 * Generate a unique avatar URL using ui-avatars.com service
 * Creates consistent avatars based on user's full name with calculated colors
 */

function getAvatarUrl($fullName = 'User', $size = 40, $rounded = true)
{
    if (empty($fullName)) {
        $fullName = 'User';
    }

    // Generate a unique color based on the name hash
    $colors = [
        'fe9630', // Primary Orange
        '22c55e', // Success Green
        'f59e0b', // Warning Amber
        '00bcd4', // Accent Cyan
        '8b5cf6', // Purple
        'ec4899', // Pink
        '06b6d4', // Sky Blue
        '14b8a6', // Teal
        'f97316', // Orange
        '6366f1'  // Indigo
    ];

    // Generate hash from name for consistent color
    $hash = hash('crc32', strtolower(trim($fullName)));
    $hashInt = hexdec($hash);
    $colorIndex = $hashInt % count($colors);
    $backgroundColor = $colors[$colorIndex];

    // Build URL parameters
    $params = [
        'name' => urlencode($fullName),
        'background' => $backgroundColor,
        'color' => 'fff',
        'size' => intval($size),
        'bold' => 'true',
        'rounded' => $rounded ? 'true' : 'false'
    ];

    return 'https://ui-avatars.com/api/?' . http_build_query($params);
}
?>

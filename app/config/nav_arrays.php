<?php

// --- Navigation Array for Super Admin ---
$super_admin_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/super_admin/dashboard', 'icon' => 'fa-solid fa-shield-halved'],
        ['title' => 'Companies', 'url' => '/super_admin/companies', 'icon' => 'fa-solid fa-building-user'],
        ['title' => 'Verification', 'url' => '/super_admin/verification', 'icon' => 'fa-solid fa-check-to-slot'],
        ['title' => 'Complaints', 'url' => '/super_admin/complaints', 'icon' => 'fa-solid fa-comment-dots'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/super_admin/profile', 'icon' => 'fa-solid fa-user-shield'],
    ],
];

// --- Navigation Array for Installer Admin ---
$installer_admin_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/installer_admin/dashboard', 'icon' => 'fa-solid fa-gauge-high'],
        ['title' => 'Fleet', 'url' => '/installer_admin/fleet', 'icon' => 'fa-solid fa-solar-panel'],
        ['title' => 'Add Customer', 'url' => '/installer_admin/add_customer', 'icon' => 'fa-solid fa-user-plus'],
        ['title' => 'Team', 'url' => '/installer_admin/team', 'icon' => 'fa-solid fa-users-gear'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/installer_admin/profile', 'icon' => 'fa-solid fa-building'],
    ],
];

// --- Navigation Array for Operation Manager ---
$operation_manager_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/operation_manager/dashboard', 'icon' => 'fa-solid fa-list-check'],
        ['title' => 'Fleet', 'url' => '/operation_manager/fleet', 'icon' => 'fa-solid fa-solar-panel'],
        ['title' => 'Add Customer', 'url' => '/operation_manager/add_customer', 'icon' => 'fa-solid fa-user-plus'],
        ['title' => 'Quotation', 'url' => '/operation_manager/quotation', 'icon' => 'fa-solid fa-file-invoice-dollar'],
        ['title' => 'Maintenance', 'url' => '/operation_manager/maintenance', 'icon' => 'fa-solid fa-wrench'],
        ['title' => 'Team', 'url' => '/operation_manager/team', 'icon' => 'fa-solid fa-users'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/operation_manager/profile', 'icon' => 'fa-solid fa-user-tie'],
    ],
];

// --- Navigation Array for Inventory Manager ---
$inventory_manager_nav = [
    'MAIN' => [
        ['title' => 'Inventory', 'url' => '/inventory_manager/inventory', 'icon' => 'fa-solid fa-boxes-stacked'],
        ['title' => 'Suppliers', 'url' => '/inventory_manager/suppliers', 'icon' => 'fa-solid fa-truck-fast'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/inventory_manager/profile', 'icon' => 'fa-solid fa-user-cog'],
    ],
];

// --- Navigation Array for Service Agent ---
$service_agent_nav = [
    'MAIN' => [
        ['title' => 'Tasks', 'url' => '/service_agent/tasks', 'icon' => 'fa-solid fa-clipboard-list'],
        ['title' => 'History', 'url' => '/service_agent/history', 'icon' => 'fa-solid fa-clock-rotate-left'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/service_agent/profile', 'icon' => 'fa-solid fa-user-gear'],
    ],
];

// --- Navigation Array for Solar Panel Owner (Customer) ---
$homeowner_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/homeowner/dashboard', 'icon' => 'fa-solid fa-chart-pie'],
        ['title' => 'Service', 'url' => '/homeowner/service', 'icon' => 'fa-solid fa-wrench'],
        ['title' => 'Shop', 'url' => '/homeowner/shop', 'icon' => 'fa-solid fa-store'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/homeowner/profile', 'icon' => 'fa-solid fa-user'],
    ],
];

?>

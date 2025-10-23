<?php

// --- Navigation Array for Super Admin ---
$super_admin_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/superadmin/dashboard', 'icon' => 'fa-solid fa-shield-halved'],
        ['title' => 'Companies', 'url' => '/superadmin/companies', 'icon' => 'fa-solid fa-building-user'],
        ['title' => 'Verification', 'url' => '/superadmin/verification', 'icon' => 'fa-solid fa-check-to-slot'],
        ['title' => 'Complaints', 'url' => '/superadmin/complaints', 'icon' => 'fa-solid fa-comment-dots'],
        ['title' => 'Reports', 'url' => '/superadmin/reports', 'icon' => 'fa-solid fa-file-lines'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/superadmin/profile', 'icon' => 'fa-solid fa-user-shield'],
    ],
];

// --- Navigation Array for Installer Admin ---
$installer_admin_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/installeradmin/dashboard', 'icon' => 'fa-solid fa-gauge-high'],
        ['title' => 'Fleet', 'url' => '/installeradmin/fleet', 'icon' => 'fa-solid fa-solar-panel'],
        ['title' => 'Managers', 'url' => '/installeradmin/managers', 'icon' => 'fa-solid fa-person-dots-from-line'],
        ['title' => 'Team', 'url' => '/installeradmin/team', 'icon' => 'fa-solid fa-users-gear'],
        ['title' => 'Reports', 'url' => '/installeradmin/reports', 'icon' => 'fa-solid fa-file-lines'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/installeradmin/profile', 'icon' => 'fa-solid fa-building'],
        ['title' => 'Help', 'url' => '/installeradmin/help', 'icon' => 'fa-solid fa-circle-question']
    ],
];

// --- Navigation Array for Operation Manager ---
$operation_manager_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/operationmanager/dashboard', 'icon' => 'fa-solid fa-list-check'],
        ['title' => 'Fleet', 'url' => '/operationmanager/fleet', 'icon' => 'fa-solid fa-solar-panel'],
        ['title' => 'Quotation', 'url' => '/operationmanager/quotation', 'icon' => 'fa-solid fa-file-invoice-dollar'],
        ['title' => 'Maintenance', 'url' => '/operationmanager/maintenance', 'icon' => 'fa-solid fa-wrench'],
        ['title' => 'Team', 'url' => '/operationmanager/team', 'icon' => 'fa-solid fa-users'],
        ['title' => 'Reports', 'url' => '/operationmanager/reports', 'icon' => 'fa-solid fa-file-lines'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/operationmanager/profile', 'icon' => 'fa-solid fa-user-tie'],
        ['title' => 'Help', 'url' => '/operationmanager/help', 'icon' => 'fa-solid fa-circle-question']
    ],
];

// --- Navigation Array for Inventory Manager ---
$inventory_manager_nav = [
    'MAIN' => [
        ['title' => 'Inventory', 'url' => '/inventorymanager/inventory', 'icon' => 'fa-solid fa-boxes-stacked'],
        ['title' => 'Suppliers', 'url' => '/inventorymanager/suppliers', 'icon' => 'fa-solid fa-truck-fast'],
        ['title' => 'Reports', 'url' => '/inventorymanager/reports', 'icon' => 'fa-solid fa-file-lines'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/inventorymanager/profile', 'icon' => 'fa-solid fa-user-cog'],
        ['title' => 'Help', 'url' => '/inventorymanager/help', 'icon' => 'fa-solid fa-circle-question']
    ],
];

// --- Navigation Array for Service Agent ---
$service_agent_nav = [
    'MAIN' => [
        ['title' => 'Tasks', 'url' => '/serviceagent/tasks', 'icon' => 'fa-solid fa-clipboard-list'],
        ['title' => 'History', 'url' => '/serviceagent/history', 'icon' => 'fa-solid fa-clock-rotate-left'],
        ['title' => 'Reports', 'url' => '/serviceagent/reports', 'icon' => 'fa-solid fa-file-lines'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/serviceagent/profile', 'icon' => 'fa-solid fa-user-gear'],
        ['title' => 'Help', 'url' => '/serviceagent/help', 'icon' => 'fa-solid fa-circle-question']
    ],
];

// --- Navigation Array for Solar Panel Owner (Customer) ---
$homeowner_nav = [
    'MAIN' => [
        ['title' => 'Dashboard', 'url' => '/homeowner/dashboard', 'icon' => 'fa-solid fa-chart-pie'],
        ['title' => 'Service', 'url' => '/homeowner/service', 'icon' => 'fa-solid fa-wrench'],
        ['title' => 'Shop', 'url' => '/homeowner/shop', 'icon' => 'fa-solid fa-store'],
        ['title' => 'Reports', 'url' => '/homeowner/reports', 'icon' => 'fa-solid fa-file-lines'],
    ],
    'PERSONAL' => [
        ['title' => 'Profile', 'url' => '/homeowner/profile', 'icon' => 'fa-solid fa-user'],
        ['title' => 'Help', 'url' => '/homeowner/help', 'icon' => 'fa-solid fa-circle-question']
    ],
];

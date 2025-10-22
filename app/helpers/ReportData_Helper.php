<?php
/**
 * Report Dummy Data File
 * Contains all sample data for reports across all roles
 * This file can be replaced with database queries later
 */

// --- Color Constants ---
define('COLOR_PRIMARY', '#fe9630');
define('COLOR_SUCCESS', '#22c55e');
define('COLOR_WARNING', '#f59e0b');
define('COLOR_ERROR', '#ef4444');
define('COLOR_INFO', '#3b82f6');
define('COLOR_ACCENT', '#06b6d4');
define('COLOR_PURPLE', '#8b5cf6');
define('COLOR_YELLOW', '#eab308');

/**
 * Helper function to get report data by role
 * All data is defined locally inside the function to avoid global scope issues
 */
function getReportDataByRole($role) {
    // --- SuperAdmin Report Data ---
    $superadmin_reports = [
        'platform_overview' => [
            'total_companies' => 156,
            'total_users' => 2847,
            'active_systems' => 12543,
            'total_generation' => '45.2M kWh',
            'platform_uptime' => '99.97%',
            'total_revenue' => 'Rs. 2.3B',
            'monthly_revenue' => 'Rs. 180M',
            'system_health' => 94.5
        ],
        'companies_report' => [
            ['name' => 'SolarTech Solutions', 'status' => 'Active', 'systems' => 1250, 'users' => 345, 'revenue' => 'Rs. 85M'],
            ['name' => 'EcoEnergy Systems', 'status' => 'Active', 'systems' => 980, 'users' => 280, 'revenue' => 'Rs. 72M'],
            ['name' => 'GreenLight Solutions', 'status' => 'Active', 'systems' => 850, 'users' => 210, 'revenue' => 'Rs. 58M'],
            ['name' => 'PowerTech Pro', 'status' => 'Active', 'systems' => 1150, 'users' => 340, 'revenue' => 'Rs. 95M'],
            ['name' => 'EcoSmart Energy', 'status' => 'Pending', 'systems' => 0, 'users' => 45, 'revenue' => 'Rs. 0'],
        ],
        'users_report' => [
            ['role' => 'SuperAdmin', 'count' => 8],
            ['role' => 'InstallerAdmin', 'count' => 12],
            ['role' => 'OperationManager', 'count' => 45],
            ['role' => 'InventoryManager', 'count' => 28],
            ['role' => 'ServiceAgent', 'count' => 487],
            ['role' => 'Homeowner', 'count' => 1867],
        ],
        'verification_report' => [
            'pending_verifications' => 23,
            'completed_verifications' => 2847,
            'rejected_verifications' => 12,
            'avg_verification_time' => '2.3 days'
        ],
        'complaints_report' => [
            'total_complaints' => 156,
            'resolved' => 142,
            'pending' => 14,
            'resolution_rate' => 91.0,
            'avg_resolution_time' => '4.2 days'
        ],
        'fleet_status' => [
            'total_systems' => 12543,
            'excellent' => 11200,
            'good' => 1100,
            'warning' => 200,
            'critical' => 43
        ],
        'energy_generation' => [
            'today' => '45.2 MWh',
            'this_week' => '280.5 MWh',
            'this_month' => '1,180.2 MWh',
            'this_year' => '14,560.8 MWh',
            'co2_avoided' => '2,340 tons'
        ]
    ];

    // --- InstallerAdmin Report Data ---
    $installeradmin_reports = [
        'fleet_status' => [
            'total_systems' => 245,
            'excellent' => 189,
            'good' => 45,
            'warning' => 8,
            'critical' => 3,
            'offline' => 0
        ],
        'systems_list' => [
            ['name' => 'Ravi Fernando', 'capacity' => '10 kWp', 'status' => 'Excellent', 'performance' => 108, 'location' => 'Colombo'],
            ['name' => 'Samanthi De Silva', 'capacity' => '8 kWp', 'status' => 'Excellent', 'performance' => 105, 'location' => 'Kandy'],
            ['name' => 'John Doe', 'capacity' => '7.5 kWp', 'status' => 'Good', 'performance' => 103, 'location' => 'Galle'],
            ['name' => 'Nimali Silva', 'capacity' => '6 kWp', 'status' => 'Critical', 'performance' => 65, 'location' => 'Matara'],
            ['name' => 'Suresh Kumar', 'capacity' => '9 kWp', 'status' => 'Warning', 'performance' => 78, 'location' => 'Jaffna'],
        ],
        'team_members' => [
            ['name' => 'Anura Kumara', 'role' => 'Installation Lead', 'tasks_completed' => 127, 'tasks_pending' => 5],
            ['name' => 'Bhanu Rajapaksha', 'role' => 'Technician', 'tasks_completed' => 98, 'tasks_pending' => 8],
            ['name' => 'Dayapal Silva', 'role' => 'Technician', 'tasks_completed' => 85, 'tasks_pending' => 6],
        ],
        'revenue_metrics' => [
            'total_revenue' => 'Rs. 85M',
            'monthly_revenue' => 'Rs. 7.5M',
            'avg_system_cost' => 'Rs. 450K',
            'profit_margin' => 28.5
        ],
        'maintenance_metrics' => [
            'scheduled' => 23,
            'completed' => 156,
            'pending' => 5,
            'cancelled' => 2
        ]
    ];

    // --- OperationManager Report Data ---
    $operationmanager_reports = [
        'fleet_operations' => [
            'total_systems' => 580,
            'active_systems' => 512,
            'maintenance' => 45,
            'offline' => 23
        ],
        'tasks_summary' => [
            ['id' => 'TSK001', 'description' => 'System inspection - Colombo', 'assigned_to' => 'Anura Kumara', 'status' => 'In-Progress', 'due_date' => '2025-10-25'],
            ['id' => 'TSK002', 'description' => 'Maintenance - Kandy', 'assigned_to' => 'Bhanu Rajapaksha', 'status' => 'Pending', 'due_date' => '2025-10-28'],
            ['id' => 'TSK003', 'description' => 'System upgrade - Galle', 'assigned_to' => 'Dayapal Silva', 'status' => 'Completed', 'due_date' => '2025-10-20'],
        ],
        'quotation_metrics' => [
            'total' => 45,
            'pending' => 12,
            'accepted' => 28,
            'rejected' => 5
        ],
        'team_performance' => [
            ['name' => 'Anura Kumara', 'role' => 'Lead', 'tasks_completed' => 189, 'tasks_pending' => 12],
            ['name' => 'Bhanu Rajapaksha', 'role' => 'Technician', 'tasks_completed' => 156, 'tasks_pending' => 8],
            ['name' => 'Dayapal Silva', 'role' => 'Technician', 'tasks_completed' => 142, 'tasks_pending' => 5],
        ],
        'revenue_metrics' => [
            'total_revenue' => 'Rs. 450M',
            'monthly_revenue' => 'Rs. 38.5M',
            'pending_payments' => 'Rs. 45M',
            'completed_payments' => 'Rs. 405M'
        ]
    ];

    // --- InventoryManager Report Data ---
    $inventorymanager_reports = [
        'inventory_summary' => [
            'total_items' => 523,
            'in_stock' => 489,
            'low_stock' => 28,
            'out_of_stock' => 6
        ],
        'stock_items' => [
            ['code' => 'SOL001', 'description' => 'Solar Panel 400W', 'quantity' => 156, 'status' => 'In-Stock', 'unit_price' => 'Rs. 45,000'],
            ['code' => 'INV001', 'description' => 'Inverter 10kW', 'quantity' => 34, 'status' => 'In-Stock', 'unit_price' => 'Rs. 180,000'],
            ['code' => 'BAT001', 'description' => 'Battery 5kWh', 'quantity' => 12, 'status' => 'Low', 'unit_price' => 'Rs. 380,000'],
            ['code' => 'CAB001', 'description' => 'Cable 6mm', 'quantity' => 2, 'status' => 'Out-Of-Stock', 'unit_price' => 'Rs. 850'],
        ],
        'suppliers' => [
            ['name' => 'SunPower Ltd', 'contact' => '+94-11-2345678', 'items_supplied' => 45, 'status' => 'Active'],
            ['name' => 'GreenEnergy Co', 'contact' => '+94-33-5678901', 'items_supplied' => 32, 'status' => 'Active'],
            ['name' => 'EcoTech Solutions', 'contact' => '+94-81-9876543', 'items_supplied' => 28, 'status' => 'Inactive'],
        ],
        'stock_movements' => [
            ['date' => '2025-10-20', 'item' => 'Solar Panel 400W', 'type' => 'In', 'quantity' => 50, 'balance' => 156],
            ['date' => '2025-10-19', 'item' => 'Inverter 10kW', 'type' => 'Out', 'quantity' => 5, 'balance' => 34],
            ['date' => '2025-10-18', 'item' => 'Battery 5kWh', 'type' => 'Out', 'quantity' => 3, 'balance' => 12],
        ],
        'cost_analysis' => [
            'total_value' => 'Rs. 12.5M',
            'monthly_cost' => 'Rs. 850K',
            'reorder_cost' => 'Rs. 2.3M',
            'storage_cost' => 'Rs. 125K'
        ]
    ];

    // --- ServiceAgent Report Data ---
    $serviceagent_reports = [
        'tasks_summary' => [
            'total' => 156,
            'completed' => 142,
            'in_progress' => 8,
            'pending' => 6
        ],
        'tasks_list' => [
            ['id' => 'TSK-SA-001', 'description' => 'System inspection', 'customer' => 'Ravi Fernando', 'status' => 'Completed', 'due_date' => '2025-10-20'],
            ['id' => 'TSK-SA-002', 'description' => 'Maintenance service', 'customer' => 'Samanthi De Silva', 'status' => 'In-Progress', 'due_date' => '2025-10-22'],
            ['id' => 'TSK-SA-003', 'description' => 'Troubleshooting', 'customer' => 'John Doe', 'status' => 'Pending', 'due_date' => '2025-10-25'],
        ],
        'performance' => [
            'average_rating' => '4.8/5',
            'customer_satisfaction' => '94%',
            'on_time_completion' => '96%',
            'quality_score' => '92%'
        ],
        'assigned_customers' => [
            ['name' => 'Ravi Fernando', 'location' => 'Colombo', 'system' => '10kWp', 'last_service' => '2025-10-15'],
            ['name' => 'Samanthi De Silva', 'location' => 'Kandy', 'system' => '8kWp', 'last_service' => '2025-10-18'],
            ['name' => 'John Doe', 'location' => 'Galle', 'system' => '7.5kWp', 'last_service' => '2025-10-10'],
        ],
        'work_hours' => [
            'total_hours' => 158,
            'billable_hours' => 145,
            'overtime' => 13,
            'average_per_task' => 2.5
        ]
    ];

    // --- Homeowner Report Data ---
    $homeowner_reports = [
        'system_overview' => [
            'capacity' => '10kWp',
            'install_date' => '2023-06-15',
            'inverter' => 'Fronius Symo 10',
            'status' => 'Active'
        ],
        'performance_today' => [
            'energy_generated' => '45.2 kWh',
            'peak_power' => '8.7 kW',
            'efficiency' => '98.2%',
            'grid_export' => '38.1 kWh'
        ],
        'financial_summary' => [
            'total_savings' => 'Rs. 12,450',
            'monthly_saving' => 'Rs. 145',
            'roi_status' => '45%',
            'payback_period' => '5.2 years'
        ],
        'environmental_impact' => [
            'co2_avoided' => '8,450 kg',
            'trees_equivalent' => '145 trees',
            'total_lifetime_generation' => '285,400 kWh',
            'renewable_percentage' => '100%'
        ],
        'service_history' => [
            ['date' => '2025-10-15', 'type' => 'Inspection', 'description' => 'Annual maintenance', 'technician' => 'Anura Kumara', 'status' => 'Completed'],
            ['date' => '2025-09-20', 'type' => 'Repair', 'description' => 'Inverter replacement', 'technician' => 'Bhanu Rajapaksha', 'status' => 'Completed'],
            ['date' => '2025-08-10', 'type' => 'Cleaning', 'description' => 'Panel cleaning', 'technician' => 'Dayapal Silva', 'status' => 'Completed'],
        ]
    ];

    // Return data based on role
    switch($role) {
        case ROLE_SUPER_ADMIN:
            return $superadmin_reports;
        case ROLE_INSTALLER_ADMIN:
            return $installeradmin_reports;
        case ROLE_OPERATION_MANAGER:
            return $operationmanager_reports;
        case ROLE_INVENTORY_MANAGER:
            return $inventorymanager_reports;
        case ROLE_SERVICE_AGENT:
            return $serviceagent_reports;
        case ROLE_HOMEOWNER:
            return $homeowner_reports;
        default:
            return [];
    }
}
?>

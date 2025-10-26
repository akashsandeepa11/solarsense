<?php
/**
 * Route Protection Helper
 * Manages role-based access control for routes
 */

// Define route access permissions
// Maps controllers to allowed roles
$ROUTE_PERMISSIONS = [
    'SuperAdmin' => [ROLE_SUPER_ADMIN],
    'InstallerAdmin' => [ROLE_INSTALLER_ADMIN],
    'OperationManager' => [ROLE_OPERATION_MANAGER],
    'InventoryManager' => [ROLE_INVENTORY_MANAGER],
    'ServiceAgent' => [ROLE_SERVICE_AGENT],
    'HomeOwner' => [ROLE_HOMEOWNER],
    'Homeowner' => [ROLE_HOMEOWNER], // Alternative spelling
    'Installeradmin' => [ROLE_INSTALLER_ADMIN], // Alternative spelling
    'Operationmanager' => [ROLE_OPERATION_MANAGER], // Alternative spelling
    'Inventorymanager' => [ROLE_INVENTORY_MANAGER], // Alternative spelling
    'Serviceagent' => [ROLE_SERVICE_AGENT], // Alternative spelling
    'Superadmin' => [ROLE_SUPER_ADMIN], // Alternative spelling
    'Pages' => [], // Public access
    'Auth' => [], // Public access
    'Example' => [], // Public access (demo controller)
];

/**
 * Check if current user can access a controller
 * @param string $controller - Controller name
 * @return bool
 */
function canAccessController($controller) {
    global $ROUTE_PERMISSIONS;
    
    // Normalize controller name for case-insensitive matching
    $controllerLower = strtolower($controller);
    
    // Try to find matching permission (case-insensitive)
    $matchedRole = null;
    foreach ($ROUTE_PERMISSIONS as $permController => $roles) {
        if (strtolower($permController) === $controllerLower) {
            $matchedRole = $roles;
            break;
        }
    }
    
    // If controller not defined in permissions, DENY access (secure by default)
    if ($matchedRole === null) {
        // Require login for undefined controllers
        return isset($_SESSION['user_id']);
    }
    
    // If no roles required (empty array), it's public
    if (empty($matchedRole)) {
        return true;
    }
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
        return false;
    }
    
    // Check if user's role is in allowed roles
    $userRole = $_SESSION['user_type'];
    return in_array($userRole, $matchedRole);
}

/**
 * Get redirect URL based on user role
 * @param string $role - User role
 * @return string - Redirect URL
 */
function getRoleDefaultRoute($role) {
    $routes = [
        ROLE_SUPER_ADMIN => URLROOT . '/SuperAdmin/dashboard',
        ROLE_INSTALLER_ADMIN => URLROOT . '/InstallerAdmin/dashboard',
        ROLE_OPERATION_MANAGER => URLROOT . '/OperationManager/dashboard',
        ROLE_INVENTORY_MANAGER => URLROOT . '/InventoryManager/inventory',
        ROLE_SERVICE_AGENT => URLROOT . '/ServiceAgent/tasks',
        ROLE_HOMEOWNER => URLROOT . '/HomeOwner/dashboard',
    ];
    
    return $routes[$role] ?? URLROOT . '/pages/index';
}

/**
 * Require authentication - redirect to login if not logged in
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        setToast('Please log in to access this page', 'error');
        redirect('auth/login');
        exit;
    }
}

/**
 * Require specific role(s) - redirect if user doesn't have permission
 * @param array|string $allowedRoles - Single role or array of allowed roles
 */
function requireRole($allowedRoles) {
    requireAuth();
    
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    $userRole = $_SESSION['user_type'] ?? null;
    
    if (!in_array($userRole, $allowedRoles)) {
        // User doesn't have permission - redirect to their default page
        setToast('You do not have permission to access this page', 'error');
        $redirectUrl = getRoleDefaultRoute($userRole);
        header('Location: ' . $redirectUrl);
        exit;
    }
}

/**
 * Block authenticated users from accessing page (e.g., login page)
 */
function blockIfAuthenticated() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
        $redirectUrl = getRoleDefaultRoute($_SESSION['user_type']);
        header('Location: ' . $redirectUrl);
        exit;
    }
}

/**
 * Check route access and redirect if unauthorized
 * @param string $controller - Controller name
 */
function protectRoute($controller) {
    // Skip protection check for Auth and Pages controllers - they are always public
    $publicControllers = ['auth', 'pages', 'example'];
    $controllerLower = strtolower($controller);
    
    if (in_array($controllerLower, $publicControllers)) {
        return; // Allow access - exit early
    }
    
    if (!canAccessController($controller)) {
        // User cannot access this controller
        if (!isset($_SESSION['user_id'])) {
            // Not logged in - redirect to login (NO toast message to avoid showing on login page)
            redirect('auth/login');
        } else {
            // Logged in but wrong role - redirect to their dashboard
            setToast('You do not have permission to access this page', 'error');
            $userRole = $_SESSION['user_type'] ?? null;
            $redirectUrl = getRoleDefaultRoute($userRole);
            header('Location: ' . $redirectUrl);
        }
        exit;
    }
}
?>

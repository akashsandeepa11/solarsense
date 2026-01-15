<?php 
class Controller{
    // To load the model
    public function model($model){
        // Convert model name to match file naming convention (lowercase or exact match)
        $modelFile = dirname(__DIR__) . '/models/' . $model . '.php';
        
        // Try exact case first
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        
        // Try lowercase version (common convention: m_auth.php, m_pages.php)
        $modelFileLower = dirname(__DIR__) . '/models/' . strtolower($model) . '.php';
        if (file_exists($modelFileLower)) {
            require_once $modelFileLower;
            return new $model();
        }
        
        // If still not found, throw error
        die("Model file not found: {$model}.php or " . strtolower($model) . ".php");
    }

    public function view($view, $data = [], $layout = 'main') {
        // Capture the content of the page view
        ob_start();
        require_once dirname(__DIR__) . "/views/{$view}.php";
        $content = ob_get_clean();
            
        // Include the chosen layout and pass $content to it
        if (file_exists(dirname(__DIR__) . "/views/layouts/{$layout}.php")) {
            require_once dirname(__DIR__) . "/views/layouts/{$layout}.php";
        } else {
            die("Layout '{$layout}' not found.");
        }
    }

    /**
     * Require authentication in controller methods
     */
    protected function requireAuth() {
        requireAuth();
    }

    /**
     * Require specific role(s) in controller methods
     * @param array|string $allowedRoles
     */
    protected function requireRole($allowedRoles) {
        requireRole($allowedRoles);
    }

    /**
     * Block authenticated users (for login/register pages)
     */
    protected function blockIfAuthenticated() {
        blockIfAuthenticated();
    }
}
?>
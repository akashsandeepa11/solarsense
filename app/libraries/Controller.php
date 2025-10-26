    <?php 
        class Controller{
            // To load the model
            public function model($model){
                require_once dirname(__DIR__) . '/models/' . $model . '.php';

                // Instantiate the model and pass it to the controller
                return new $model();
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
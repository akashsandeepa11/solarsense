<?php
    class Core{
        // URL Format --> /controller/method/params
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct(){
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $url = $this->getURL();

            // If controller segment provided, ensure the controller file exists
            if($url && isset($url[0])){
                $requestedControllerFile = __DIR__ . '/../controllers/' . ucwords($url[0]) . '.php';
                if(file_exists($requestedControllerFile)){
                    $this->currentController = ucwords($url[0]);
                    unset($url[0]);
                } else {
                    // Controller file not found -> 404
                    $this->send404();
                    return;
                }
            }

            // Check route protection BEFORE instantiating controller
            protectRoute($this->currentController);

            // Require the controller file
            require_once __DIR__ . '/../controllers/' . $this->currentController . '.php';

            // Instantiate the controller
            $this->currentController = new $this->currentController;

            // If method provided, ensure it exists on the controller
            if(isset($url[1])){
                if(method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                } else {
                    // Method not found -> 404
                    $this->send404();
                    return;
                }
            } else {
                // No method given: ensure default method exists
                if(!method_exists($this->currentController, $this->currentMethod)){
                    $this->send404();
                    return;
                }
            }

            // Parameters
            $this->params = $url ? array_values($url) : [];

            // Call controller method
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }

        /**
         * Send 404 response and render 404 view.
         */
        protected function send404(){
            if(!headers_sent()){
                header('HTTP/1.1 404 Not Found');
            }

            // Prefer Pages controller view method so layouts are applied
            $pagesFile = __DIR__ . '/../controllers/Pages.php';
            $viewFile = __DIR__ . '/../views/pages/404.php';

            if(file_exists($pagesFile)){
                require_once $pagesFile;
                if(class_exists('Pages')){
                    $pages = new Pages;
                    if(method_exists($pages, 'error404')){
                        // If Pages::error404 exists, call it
                        $pages->error404();
                        exit;
                    }
                    if(method_exists($pages, 'view')){
                        $pages->view('pages/404');
                        exit;
                    }
                }
            }

            // Fallback to direct include
            if(file_exists($viewFile)){
                include $viewFile;
                exit;
            }

            // Final fallback plain text
            echo '<h1>404 - Page not found</h1>';
            exit;
        }

        public function getURL(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
        }
    }
?>
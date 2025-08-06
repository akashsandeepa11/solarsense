<?php 
    class Core{
        //URl Format--> /controller/method/params
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];
        
        public function __construct(){
            // print_r($this->getURL());

            $url = $this->getURL();

            if($url && isset($url[0]) && file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
                // If the Controller exists, then load it
                $this->currentController = ucwords($url[0]);

                // Unset the Controller in the URL
                unset($url[0]);

                // Call the Controller
                require_once '../app/controllers/'.$this->currentController.'.php';

                // Instantiate the controller
                $this->currentController = new $this->currentController;

                // Check whether the method exits in the controller or not
                if(isset($url[1])){
                    if(method_exists($this->currentController, $url[1])){
                        $this->currentMethod = $url[1];

                        unset($url[1]);
                    }
                }

                // get param list
                $this->params = $url? array_values($url) : [];

                // Call the method and param list
                call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

            }

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
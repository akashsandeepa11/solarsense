    <?php 
        class Controller{
            // To load the model
            public function model($model){
                require_once '../app/models/'.$model.'.php';

                // Instantiate the model and pass it to the controller
                return new $model();
            }

            // To load the view
            // public function view($view, $data = []){
            //     if(file_exists('../app/views/'.$view.'.php')){
            //         require_once '../app/views/'.$view.'.php';
            //     }else{
            //         die('Coresponding view does not exists!');
            //     }
            // }

            public function view($view, $data = [], $layout = 'main') {
                // Capture the content of the page view
                ob_start();
                require_once "../app/views/{$view}.php";
                $content = ob_get_clean();
                    
                // Include the chosen layout and pass $content to it
                if (file_exists("../app/views/layouts/{$layout}.php")) {
                    require_once "../app/views/layouts/{$layout}.php";
                } else {
                    die("Layout '{$layout}' not found.");
                }
            }
        }
    ?>
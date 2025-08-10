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

            public function view($view, $data = [], $layout = 'layouts/main') {
                if (file_exists('../app/views/' . $view . '.php')) {
                    // Make $data keys available as variables
                    extract($data);
                
                    // Path to actual page content
                    $viewPath = '../app/views/' . $view . '.php';
                
                    // If a layout is specified, load it
                    if ($layout && file_exists('../app/views/' . $layout . '.php')) {
                        require_once '../app/views/' . $layout . '.php';
                    } else {
                        // No layout, load directly
                        require_once $viewPath;
                    }
                } else {
                    die('View "' . $view . '" does not exist!');
                }
            }
        }
    ?>
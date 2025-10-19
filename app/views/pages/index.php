<?php require APPROOT.'/views/inc/header.php'; ?>

<div  class="p-4 bg-surface">
    
                    <form action="#" method="post">
                        <div class="form-group mb-3">
                            <?php
                            // Configuration for the Email input field with an icon
                            $inputConfig = [
                                'id'    => 'email',
                                'name'  => 'email',
                                'label' => 'Email Address', // This becomes the placeholder
                                'type'  => 'email',
                                'icon'  => 'fas fa-envelope' // Font Awesome icon class
                            ];
                            // Assuming your new component file is named 'styled_input.php'
                            require APPROOT . '/views/inc/components/input_field.php';
                            ?>
                        </div>

                        <button class="btn btn-primary rounded-xl">
  Click Me
</button>
                    </form>
                </div>  
</div>


<?php require APPROOT.'/views/inc/footer.php'; ?>

<?php require APPROOT.'/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center">Users Form</h2>
                    <p class="text-center">Example form using the new input component</p>
                    
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
                            require APPROOT . '/views/inc/components/inputField.php';
                            ?>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require APPROOT.'/views/inc/footer.php'; ?>

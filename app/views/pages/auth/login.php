<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/auth/login.css">

<div class="login-container d-flex">

    <!-- Left Promotional Panel -->
    <div class="login-promo-panel">
        <div>
            <h1>SolarSense</h1>
            <p>Unlock the true potential of your solar investment.</p>
            <a href="#" class="btn btn-light-orange">Read More</a>
        </div>
    </div>

    <!-- Right Login Form Panel -->
    <div class="login-form-panel bg-surface">
        <div class="login-form-wrapper">
            <div class="text-center">
                <h2>Welcome to SolarSense</h2>
                <p class="text-secondary mb-10">Sign in to access your dashboard.</p>
            </div>
            
            <form action="<?php echo URLROOT?>/auth/login" method="post">
                <div class="form-group">
                    <?php 
                        $inputConfig = [
                            'id'    => 'email', 
                            'name'  => 'email', 
                            'label' => 'Email', 
                            'type'  => 'email', 
                            'icon'  => 'fas fa-envelope', 
                            'value' => $data['email'],
                            'error' => $data['email_err'],
                        ]; 
                        // This require points to your reusable input field component
                        require APPROOT . '/views/inc/components/input_field.php'; 
                    ?>
                </div>
                <div class="form-group">
                    <?php 
                    $inputConfig = [
                        'id'    => 'password', 
                        'name'  => 'password', 
                        'label' => 'Password', 
                        'type'  => 'password', 
                        'icon'  => 'fas fa-lock',
                        'value' => $data['password'],
                        'error' => $data['password_err'],
                    ]; 
                    // This require points to your reusable input field component
                    require APPROOT . '/views/inc/components/input_field.php'; 
                    ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block rounded-lg mt-8">Login</button>
                <a href="<?php echo URLROOT?>/auth/forgot-password" class="forgot-password text-secondary text-decoration-none d-block text-center mt-4 text-sm">Forgot Password</a>
            </form>
        </div>
    </div>  
</div>



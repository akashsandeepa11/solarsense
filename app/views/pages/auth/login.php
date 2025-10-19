<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/auth/login.css">

<?php
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
?>

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
            
            <form action="/login" method="post">
                <div class="form-group">
                    <?php 
                    $inputConfig = [
                        'id'    => 'username', 
                        'name'  => 'username', 
                        'label' => 'Username', 
                        'type'  => 'username', 
                        'icon'  => 'fas fa-person', 
                        'value' => $username
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
                        'value' => $password
                    ]; 
                    // This require points to your reusable input field component
                    require APPROOT . '/views/inc/components/input_field.php'; 
                    ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block rounded-lg mt-8">Login</button>
                <a href="/forgot-password" class="forgot-password text-secondary text-decoration-none d-block text-center mt-4 text-sm">Forgot Password</a>
            </form>
        </div>
    </div>
</div>



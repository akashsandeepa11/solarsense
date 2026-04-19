<?php
$sentSuccess = $data['sent'] ?? false;
$email       = $data['email'] ?? '';
$email_err   = $data['email_err'] ?? '';
?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/auth/login.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/auth/forgot_password.css">

<div class="login-container d-flex">

    <!-- Left Promotional Panel -->
    <div class="login-promo-panel">
        <div>
            <a href="<?php echo URLROOT; ?>" style="text-decoration: none; color: inherit;">
                <h1>SolarSense</h1>
            </a>
            <p>Don't worry — we'll help you get back into your account.</p>
            <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-light-orange">Back to Login</a>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="login-form-panel bg-surface">
        <div class="login-form-wrapper">

            <?php if ($sentSuccess): ?>
                <!-- Success State -->
                <div class="fp-success-box">
                    <div class="fp-success-icon">
                        <i class="fas fa-envelope-circle-check"></i>
                    </div>
                    <h2>Check your email</h2>
                    <p class="text-secondary">
                        We've sent a password reset link to <strong><?php echo htmlspecialchars($email); ?></strong>.
                        The link expires in <strong>1 hour</strong>.
                    </p>
                    <p class="text-secondary text-sm mt-4">Didn't receive it? Check your spam folder or
                        <a href="<?php echo URLROOT; ?>/auth/forgot-password" class="fp-resend-link">try again</a>.
                    </p>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-primary btn-block rounded-lg mt-8">
                        Back to Login
                    </a>
                </div>

            <?php else: ?>
                <!-- Form State -->
                <div class="text-center mb-6">
                    <div class="fp-icon-wrap">
                        <i class="fas fa-lock-open"></i>
                    </div>
                    <h2>Forgot Password?</h2>
                    <p class="text-secondary mb-10">Enter your email and we'll send you a reset link.</p>
                </div>

                <form action="<?php echo URLROOT; ?>/auth/forgot-password" method="post">
                    <div class="form-group">
                        <?php
                        $inputConfig = [
                            'id'    => 'email',
                            'name'  => 'email',
                            'label' => 'Email Address',
                            'type'  => 'email',
                            'icon'  => 'fas fa-envelope',
                            'value' => $email,
                            'error' => $email_err,
                        ];
                        require APPROOT . '/views/inc/components/input_field.php';
                        ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block rounded-lg mt-8" id="btn-send-reset">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                    <a href="<?php echo URLROOT; ?>/auth/login"
                       class="forgot-password text-secondary text-decoration-none d-block text-center mt-4 text-sm">
                        &larr; Back to Login
                    </a>
                </form>
            <?php endif; ?>

        </div>
    </div>
</div>

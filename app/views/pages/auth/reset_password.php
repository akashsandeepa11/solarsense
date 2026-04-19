<?php
$token         = $data['token'] ?? '';
$password_err  = $data['password_err'] ?? '';
$confirm_err   = $data['confirm_err'] ?? '';
$invalid_token = $data['invalid_token'] ?? false;
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
            <p>Choose a strong, unique password to protect your account.</p>
            <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-light-orange">Back to Login</a>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="login-form-panel bg-surface">
        <div class="login-form-wrapper">

            <?php if ($invalid_token): ?>
                <!-- Invalid / Expired Token -->
                <div class="fp-success-box">
                    <div class="fp-success-icon fp-icon-error">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <h2>Link Expired</h2>
                    <p class="text-secondary">
                        This password reset link is invalid or has expired.
                        Reset links are only valid for <strong>1 hour</strong>.
                    </p>
                    <a href="<?php echo URLROOT; ?>/auth/forgot-password" class="btn btn-primary btn-block rounded-lg mt-8">
                        Request a New Link
                    </a>
                </div>

            <?php else: ?>
                <!-- Form State -->
                <div class="text-center mb-6">
                    <div class="fp-icon-wrap">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2>Reset Password</h2>
                    <p class="text-secondary mb-10">Enter your new password below.</p>
                </div>

                <form action="<?php echo URLROOT; ?>/auth/reset-password" method="post">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="form-group">
                        <?php
                        $inputConfig = [
                            'id'    => 'password',
                            'name'  => 'password',
                            'label' => 'New Password',
                            'type'  => 'password',
                            'icon'  => 'fas fa-lock',
                            'value' => '',
                            'error' => $password_err,
                        ];
                        require APPROOT . '/views/inc/components/input_field.php';
                        ?>
                    </div>

                    <div class="form-group mt-4">
                        <?php
                        $inputConfig = [
                            'id'    => 'confirm_password',
                            'name'  => 'confirm_password',
                            'label' => 'Confirm New Password',
                            'type'  => 'password',
                            'icon'  => 'fas fa-lock',
                            'value' => '',
                            'error' => $confirm_err,
                        ];
                        require APPROOT . '/views/inc/components/input_field.php';
                        ?>
                    </div>

                    <!-- Password strength hints -->
                    <ul class="fp-hints text-sm text-secondary mt-3">
                        <li id="hint-len"><i class="fas fa-circle-dot"></i> At least 8 characters</li>
                        <li id="hint-upper"><i class="fas fa-circle-dot"></i> One uppercase letter</li>
                        <li id="hint-num"><i class="fas fa-circle-dot"></i> One number</li>
                    </ul>

                    <button type="submit" class="btn btn-primary btn-block rounded-lg mt-8" id="btn-reset">
                        <i class="fas fa-shield-halved me-2"></i> Reset Password
                    </button>
                </form>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
(function () {
    const pwInput = document.getElementById('password');
    if (!pwInput) return;

    const rules = [
        { id: 'hint-len',   test: v => v.length >= 8 },
        { id: 'hint-upper', test: v => /[A-Z]/.test(v) },
        { id: 'hint-num',   test: v => /[0-9]/.test(v) },
    ];

    pwInput.addEventListener('input', function () {
        rules.forEach(r => {
            const el = document.getElementById(r.id);
            if (!el) return;
            el.classList.toggle('fp-hint-ok', r.test(this.value));
        });
    });
})();
</script>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Your Password – SolarSense</title>
</head>
<body>
  <p><strong>SolarSense</strong> – Solar Energy Management Platform</p>
  <hr>

  <p>Hi <strong><?php echo htmlspecialchars($data['name']); ?></strong>,</p>
  <p>We received a request to reset the password for your SolarSense account associated with <strong><?php echo htmlspecialchars($data['email']); ?></strong>.</p>

  <p><strong>This link expires in 1 hour.</strong> If you didn't request this, you can safely ignore this email.</p>

  <p>Click the link below to reset your password:<br>
  <?php echo $data['reset_url']; ?></p>

  <p>If you did not request a password reset, no changes have been made to your account.</p>
  <p>– The SolarSense Team</p>

  <hr>
  <small>&copy; <?php echo date('Y'); ?> SolarSense | <a href="<?php echo URLROOT; ?>">Visit our website</a></small>
</body>
</html>

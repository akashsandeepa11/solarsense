<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Welcome to SolarSense</title>
</head>
<body>
  <p><strong>SolarSense</strong> – Solar Energy Management Platform</p>
  <hr>

  <p>Hi <strong><?php echo htmlspecialchars($data['username']); ?></strong>,</p>
  <p>Your SolarSense account has been created. Use the credentials below to log in:</p>

  <p>
    <strong>Username:</strong> <?php echo htmlspecialchars($data['username']); ?><br>
    <strong>Password:</strong> <?php echo htmlspecialchars($data['password']); ?>
  </p>

  <?php if (!empty($data['reset_url'])): ?>
  <p>
    We recommend setting your own secure password. This link expires in <strong>24 hours</strong>:<br>
    <?php echo $data['reset_url']; ?>
  </p>
  <?php endif; ?>

  <p>If you have any questions, contact your administrator.</p>
  <p>– The SolarSense Team</p>

  <hr>
  <small>&copy; <?php echo date('Y'); ?> SolarSense | <a href="<?php echo URLROOT; ?>">Visit our website</a></small>
</body>
</html>
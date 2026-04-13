<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Your Password – SolarSense</title>
<style>
  body { margin: 0; padding: 0; background: #f4f7fb; font-family: 'Segoe UI', Arial, sans-serif; }
  .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #fe9630 0%, #c26300 100%); padding: 40px 32px; text-align: center; }
  .header h1 { margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px; }
  .header p { margin: 6px 0 0; color: rgba(255,255,255,0.85); font-size: 14px; }
  .body { padding: 36px 40px; color: #2d3436; }
  .body h2 { font-size: 20px; margin: 0 0 12px; color: #1a1a2e; }
  .body p { font-size: 15px; line-height: 1.7; margin: 0 0 16px; color: #636e72; }
  .btn-wrap { text-align: center; margin: 32px 0; }
  .btn { display: inline-block; background: linear-gradient(135deg, #fe9630, #d67000); color: #ffffff !important; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-size: 15px; font-weight: 600; letter-spacing: 0.3px; }
  .link-box { background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 8px; padding: 12px 16px; margin: 16px 0; word-break: break-all; font-size: 13px; color: #495057; }
  .expiry { background: #fff3e0; border-left: 4px solid #fe9630; border-radius: 6px; padding: 12px 16px; font-size: 14px; color: #7d4e00; margin-bottom: 24px; }
  .footer { background: #f8f9fa; padding: 24px 40px; text-align: center; font-size: 12px; color: #b2bec3; border-top: 1px solid #eee; }
  .footer a { color: #fe9630; text-decoration: none; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>☀️ SolarSense</h1>
    <p>Solar Energy Management Platform</p>
  </div>
  <div class="body">
    <h2>Password Reset Request</h2>
    <p>Hi <?php echo htmlspecialchars($data['name']); ?>,</p>
    <p>We received a request to reset the password for your SolarSense account associated with <strong><?php echo htmlspecialchars($data['email']); ?></strong>.</p>

    <div class="expiry">
      ⏰ <strong>This link expires in 1 hour.</strong> If you didn't request this, you can safely ignore this email.
    </div>

    <div class="btn-wrap">
      <a href="<?php echo $data['reset_url']; ?>" class="btn">Reset My Password</a>
    </div>

    <p style="text-align:center; font-size:13px; color:#b2bec3;">Button not working? Copy and paste this link into your browser:</p>
    <div class="link-box"><?php echo $data['reset_url']; ?></div>

    <p>If you did not request a password reset, no changes have been made to your account.</p>
    <p>Stay secure,<br><strong>The SolarSense Team</strong></p>
  </div>
  <div class="footer">
    &copy; <?php echo date('Y'); ?> SolarSense &nbsp;|&nbsp;
    <a href="<?php echo URLROOT; ?>">Visit our website</a>
  </div>
</div>
</body>
</html>

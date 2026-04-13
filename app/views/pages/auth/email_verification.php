<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to SolarSense</title>
<style>
  body { margin: 0; padding: 0; background: #f4f7fb; font-family: 'Segoe UI', Arial, sans-serif; }
  .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #fe9630 0%, #c26300 100%); padding: 40px 32px; text-align: center; }
  .header h1 { margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px; }
  .header p { margin: 6px 0 0; color: rgba(255,255,255,0.85); font-size: 14px; }
  .body { padding: 36px 40px; color: #2d3436; }
  .body h2 { font-size: 20px; margin: 0 0 12px; color: #1a1a2e; }
  .body p { font-size: 15px; line-height: 1.7; margin: 0 0 16px; color: #636e72; }
  .credentials-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 18px 20px; margin: 20px 0; }
  .credentials-box table { width: 100%; border-collapse: collapse; }
  .credentials-box td { padding: 6px 0; font-size: 15px; color: #2d3436; }
  .credentials-box td:first-child { font-weight: 600; width: 110px; color: #636e72; }
  .credentials-box .pw-value { font-family: 'Courier New', monospace; background: #e9ecef; padding: 3px 8px; border-radius: 4px; font-size: 14px; letter-spacing: 1px; }
  .divider { border: none; border-top: 1px solid #f0f0f0; margin: 24px 0; }
  .set-pw-section { background: linear-gradient(135deg, #fff8f0, #fff3e0); border: 1px solid #ffe0b2; border-radius: 10px; padding: 20px 24px; margin: 20px 0; text-align: center; }
  .set-pw-section p { margin: 0 0 16px; font-size: 14px; color: #7d4e00; }
  .btn { display: inline-block; background: linear-gradient(135deg, #fe9630, #d67000); color: #ffffff !important; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-size: 15px; font-weight: 600; letter-spacing: 0.3px; }
  .link-box { background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 8px; padding: 10px 14px; margin-top: 12px; word-break: break-all; font-size: 12px; color: #495057; }
  .expiry-note { font-size: 12px; color: #adb5bd; margin-top: 8px; }
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
    <h2>Welcome aboard, <?php echo htmlspecialchars($data['username']); ?>!</h2>
    <p>Your SolarSense account has been created by the administrator. You can log in right away using the temporary credentials below.</p>

    <div class="credentials-box">
      <table>
        <tr>
          <td>Username:</td>
          <td><?php echo htmlspecialchars($data['username']); ?></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><span class="pw-value"><?php echo htmlspecialchars($data['password']); ?></span></td>
        </tr>
      </table>
    </div>

    <hr class="divider">

    <?php if (!empty($data['reset_url'])): ?>
    <div class="set-pw-section">
      <p>🔐 <strong>We recommend setting your own secure password.</strong><br>Click the button below — this link expires in <strong>24 hours</strong>.</p>
      <a href="<?php echo $data['reset_url']; ?>" class="btn">Set My Password</a>
      <div class="link-box"><?php echo $data['reset_url']; ?></div>
      <p class="expiry-note">If the button doesn't work, copy and paste the link above into your browser.</p>
    </div>
    <?php endif; ?>

    <p>If you have any questions, feel free to reach out to your administrator.</p>
    <p>Welcome to the team,<br><strong>The SolarSense Team</strong></p>
  </div>
  <div class="footer">
    &copy; <?php echo date('Y'); ?> SolarSense &nbsp;|&nbsp;
    <a href="<?php echo URLROOT; ?>">Visit our website</a>
  </div>
</div>
</body>
</html>
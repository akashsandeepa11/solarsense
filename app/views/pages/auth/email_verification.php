<style>
    .container {
        font-family: Arial, sans-serif;
        padding: 20px;
        border: 1px solid #ddd;
    }

    .header {
        color: #2d3436;
        font-size: 20px;
        font-weight: bold;
    }

    .credentials {
        background: #f9f9f9;
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
    }
</style>

<div class="container">
    <div class="header">Welcome to SolarSense!</div>
    <p>Hello,</p>
    <p>Your account has been created by the administrator. Use the credentials below to log in:</p>

    <div class="credentials">
        <strong>Username:</strong> <?php echo $data['username']; ?><br>
        <strong>Password:</strong> <?php echo $data['password']; ?>
    </div>

    <!-- <p>Please change your password after your first login for security.</p> -->
    <p>Regards,<br>SolarSense Team</p>
</div>
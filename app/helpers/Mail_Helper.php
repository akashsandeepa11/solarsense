<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function sendWelcomeEmail($email, $username, $password)
{
    $mail = new PHPMailer(true);

    try {
        $debugLog = '';

        // Server Settings
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];

        // CRITICAL: SSL Bypass (Keep this from the Helper)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Account Credentials - SolarSense';

        // Logging settings
        $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function($str, $level) use (&$debugLog) {
            $debugLog .= '[' . date('Y-m-d H:i:s') . '] ' . $str . PHP_EOL;
        };

        $data = ['username' => $username, 'password' => $password];
        ob_start();
        require APPROOT . '/views/pages/auth/email_verification.php';
        $mail->Body = ob_get_clean();

        if($mail->send()) {
            // Reusing M_Mail Logic: Log to Database
            try {
                $db = new Database(); 
                $db->query("INSERT INTO email_logs (recipient, sent_at) VALUES (:email, NOW())");
                $db->bind(':email', $email);
                $db->execute();
            } catch (Exception $e) {
                error_log("DB Logging failed: " . $e->getMessage());
            }

            return true;
        }

    } catch (Exception $e) {
        // Write details to your log file
        $logFile = APPROOT . '/logs/mail_debug.log';
        file_put_contents($logFile, $debugLog . $e->getMessage(), FILE_APPEND);
        return false;
    }
}
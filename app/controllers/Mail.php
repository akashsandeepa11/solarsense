<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Mail extends Controller
{
    private $mailModel;

    public function __construct()
    {
        $this->mailModel = $this->model('M_Mail');
    }
    public function testview()
    {
        $data = ['username' => 'Admin', 'password' => 'Pass123'];
        $this->view('email_verification', $data);
    }

    public function sendWelcomeEmail($email, $username, $password)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'aindiramanayake@gmail.com';
            $mail->Password = 'your-app-password'; // Use App Password, not main password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('aindiramanayake@gmail.com', 'SolarSense Admin');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Account Credentials - SolarSense';

            // Pass data to the view/template
            $data = [
                'username' => $username,
                'password' => $password
            ];

            // Capture the view output to use as the email body
            ob_start();
            require APPROOT . '../app/views/pages/auth/email_verification.php';
            $body = ob_get_clean();

            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
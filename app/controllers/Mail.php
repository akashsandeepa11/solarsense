<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mail extends Controller
{
    private $mailModel;

    public function __construct()
    {
        $this->mailModel = $this->model('M_Mail');
    }

    public function sendWelcomeEmail($email, $username, $password)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->Port = $_ENV['MAIL_PORT'];


            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Recipients
            $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Account Credentials - SolarSense';

            // debug
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';


            // Pass data to the view/template
            $data = [
                'username' => $username,
                'password' => $password
            ];

            // Capture the view output to use as the email body
            ob_start();
            require APPROOT . '/views/pages/auth/email_verification.php';
            $body = ob_get_clean();

            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function testSend()
    {
        $result = $this->sendWelcomeEmail(
            'aindiramanayake@gmail.com',
            'testuser',
            '123456'
        );

        echo $result ? 'Email sent successfully' : 'Email failed';
    }

}
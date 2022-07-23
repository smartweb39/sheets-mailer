<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Notify {
    public $user;
    public $pass;
    public $host;

    public function __construct($host, $user, $pass)
    {
        $this->user = $user;
        $this->host = $host;
        $this->pass = $pass;
    }

    public function send($to, $subject, $body) 
    {
        try {
            $mail             = new PHPMailer(true);
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->user;
            $mail->Password   = $this->pass;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->Subject    = $subject;
            $mail->Body       = $body;
            $mail->isSMTP();
            $mail->setFrom($this->user);
            $mail->addAddress($to);
            $mail->isHTML(false);
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
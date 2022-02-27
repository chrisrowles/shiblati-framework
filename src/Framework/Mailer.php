<?php

namespace Shiblati\Framework;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Shiblati\Framework\Config\MailerConfig;

class Mailer
{
    protected PHPMailer $mailer;

    public function __construct(MailerConfig $config)
    {
        $this->mailer = new PHPMailer(true);

        if ($config->enabled) {
            $this->mailer->isSMTP();
        }

        $this->mailer->SMTPDebug  = $config->debug;
        $this->mailer->SMTPAuth   = $config->auth;
        $this->mailer->SMTPSecure = $config->encryption;

        $this->mailer->Host = $config->host;
        $this->mailer->Port = $config->port;

        $this->mailer->Username = $config->username;
        $this->mailer->Password = $config->password;
    }

    /**
     * @throws Exception
     */
    public function send(string $to, string $from, string $subject, string $body)
    {
        $this->mailer->setFrom($from);

        $this->mailer->addAddress($to);

        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;

        $this->mailer->send();
    }
}
<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailManager
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function registrationEmail($user)
    {
        $email = (new TemplatedEmail())
            ->from('contactadmin@gmail.com')
            ->to($user->getEmail())
            ->subject('Request for account confirmation')
            ->htmlTemplate('emails/registration_email.html.twig')
            ->context([
                'user' => $user
            ]);

        $this->mailer->send($email);
    }
    public function forgotPasswordEmail($user)
    {
        $email = (new TemplatedEmail())
            ->from('contactadmin@gmail.com')
            ->to($user->getEmail())
            ->subject('Reset Password - Contact Admin')
            ->htmlTemplate('emails/forgot_password_email.html.twig')
            ->context([
                'user' => $user
            ]);

        $this->mailer->send($email);
    }

}
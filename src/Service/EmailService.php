<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $emetteur
     * @param string $destinataire
     * @param string $objet
     * @param string $template
     * @param array $contexte
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function envoyerEmail(string $emetteur,string $destinataire,string $objet,
                                 string $template, array $contexte) : void {
        $mail = new TemplatedEmail();
        $mail->from($emetteur)
            ->to($destinataire)
            ->subject($objet)
            ->htmlTemplate($template)
            ->context($contexte);

        //envoi du mail
        $this->mailer->send($mail);
    }

}
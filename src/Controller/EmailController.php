<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(MailerInterface $mailer): Response
    {
        $mail = new TemplatedEmail();
        $mail->from("emetteur@gmail.com")
             ->to("destinataire@gmail.com")
             ->subject("envoi d'un mail pour test")
             ->htmlTemplate("email/email.html.twig");

        //envoi du mail
        $mailer->send($mail);
        return $this->redirectToRoute("app_articles");
    }
}

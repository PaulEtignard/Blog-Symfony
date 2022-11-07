<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{

    #[Route('/contact', name: 'app_contact',methods: ['GET','POST'],priority: 1)]

    public function index(EmailService $emailService,ContactRepository $contactRepository,Request $request): Response
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class,$contact);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted()){
            $contact->setCreatedAt(new \DateTime());
            $contactRepository->add($contact,true);

            $emailService->envoyerEmail($contact->getEmail(),"admin@blog.fr",$contact->getSujet(),"email/email.html.twig",[
                "contenu"=>$contact->getContenu(),
            ]);
            return $this->redirectToRoute("app_articles");
        }
        return $this->renderForm('email/contact.html.twig',[
            'formContact'=>$formContact
        ]);

    }


}

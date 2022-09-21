<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienvenueController extends AbstractController
{
    #[Route('/bienvenue', name: 'app_bienvenue')]
    public function bienvenue(): Response
    {
        return $this->render('bienvenue/index.html.twig');
    }
    #[Route('/bienvenue/{nom}', name: 'app_bienvenue_personne')]
    public function bienvenuePersonne($nom): Response
    {

        return $this->render('bienvenue/bienvenue-personne.html.twig', [
            "nom" => $nom
        ]);
    }
    #[Route('/bienvenus', name: 'app_bienvenus')]
    public function bienvenus(): Response
    {
        //declarer un tableau de 3 prénom
        $noms = ["Pierre","Paul","Jaque"];

        //la vue afficge la bienvenue aux 3 prénoms
        return $this->render('bienvenue/bienvenus.html.twig',[
            "noms" => $noms
        ]);
    }
}



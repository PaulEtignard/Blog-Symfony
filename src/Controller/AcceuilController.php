<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    public function __construct(ArticleRepository $repository){
        $this->repository = $repository;
    }
    #[Route('/', name: 'app_acceuil')]

    public function getArticles(): Response
    {
        //recuperer les info dans la DB
        //le controler fait appel au modèle (classe du modele) afin de récuperer la liste des articles
        //$repository = new ArticleRepository();
        $articles = $this->repository->findBy([],["createdAt"=>"DESC"],limit: 10);


        return $this->render('acceuil/index.html.twig',[
            "articles" => $articles
        ]);
    }
}

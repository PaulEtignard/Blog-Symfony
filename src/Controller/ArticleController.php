<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $repository;

    //demander a symfony d'injecter une instance de ArticleRepository
    public function __construct(ArticleRepository $repository){
        $this->repository = $repository;
    }
    #[Route('/articles', name: 'app_articles')]
    //A l'appel de la méthode, synfony va créer un objet de la classe articleRepository
        // et le passer en paramètre de la méthode
        //Mécanisme : INJECTION DE DEPENDANCE
    public function getArticles(): Response
    {
        //recuperer les info dans la DB
        //le controler fait appel au modèle (classe du modele) afin de récuperer la liste des articles
        //$repository = new ArticleRepository();
        $articles = $this->repository->findBy([],["createdAt"=>"DESC"]);


        return $this->render('article/index.html.twig',[
            "articles" => $articles
            ]);
    }

    #[Route('/article/{slug}', name: 'app_article_slug')]
    public function getArticleById($slug): Response
    {

        $article = $this->repository->findOneBy(["slug"=>$slug]);


        return $this->render('article/articles.html.twig',[
            "article" => $article,
        ]);
    }



}

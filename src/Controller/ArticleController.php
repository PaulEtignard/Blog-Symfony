<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $repository;
    private CommentaireRepository $commentaireRepository;

    //demander a symfony d'injecter une instance de ArticleRepository
    public function __construct(ArticleRepository $repository, CommentaireRepository $commentaireRepository){
        $this->repository = $repository;
        $this->commentaireRepository = $commentaireRepository;
    }
    #[Route('/articles', name: 'app_articles')]
    //A l'appel de la méthode, synfony va créer un objet de la classe articleRepository
        // et le passer en paramètre de la méthode
        //Mécanisme : INJECTION DE DEPENDANCE
    public function getArticles(PaginatorInterface $paginator,Request $request): Response
    {

        $articles = $paginator->paginate
        (
            $this->repository->findBy([],["createdAt"=>"DESC"]),
            $request->query->getInt("page",1),
            10
        );



        return $this->render('article/index.html.twig',[
            "articles" => $articles
            ]);
    }

    #[Route('/article/{slug}', name: 'app_article_slug',methods:['GET','POST'],priority:1)]
    public function getArticleById($slug,Request $request): Response
    {
        $commentaire = new Commentaire();
        $article = $this->repository->findOneBy(["slug"=>$slug]);
        $formCommentaire = $this->createForm(CommentaireType::class,$commentaire);
        $formCommentaire->handleRequest($request);

        if ($formCommentaire->isSubmitted()){
            $commentaire->setCreatedAt(new \DateTime())
                ->setArticle($article);
            $this->commentaireRepository->add($commentaire,true);
            return $this->redirectToRoute("app_articles");
        }
        return $this->renderForm('article/articles.html.twig',[
            "article" => $article,
            "formcommentaire" => $formCommentaire
        ]);
    }
    #[Route('/article/creerArticle', name: 'app_articles_creer',methods: ['GET','POST'], priority: 2)]
    public function insert(SluggerInterface $slugger, Request $request) : Response {
        $article = new Article();
        // Création du formulaire
        $formArticle = $this->createForm(ArticleType::class, $article);

        // Reconnaitre si le formulaire a été soumis ou non
        $formArticle->handleRequest($request);
        // Est-ce que le formulaire a été soumis
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $article->setSlug($slugger->slug($article->getTitre())->lower())
                ->setCreatedat(new \DateTime());
            // Insérer l'article dans la bdd
            $this->repository->add($article, true);
            return $this->redirectToRoute("app_articles");
        }

        // Appel de la vue twig permettant d'afficher le formulaire
        return $this->renderForm('article/creerArticle.html.twig', [
            "formArticle"=>$formArticle
        ]);

        /*$article->setTitre('Nouvel article 2')
            ->setContenu("Contenu du nouvel article 2")
            ->setSlug($slugger->slug($article->getTitre())->lower())
            ->setCreatedat(new \DateTime());
        // Only avec Symfony 6 !
        $this->articleRepository->add($article, true);
        return $this->redirectToRoute("app_articles");*/
    }



}

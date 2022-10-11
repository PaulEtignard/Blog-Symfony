<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private CategorieRepository $repository;
    public function __construct(CategorieRepository $repository){
        $this->repository = $repository;
    }
    #[Route('/categories', name: 'app_categories')]
    public function getCategorie(): Response
    {
        $categories = $this->repository->findBy([],["titre"=>"ASC"]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]
    public function getCategorieBySlug($slug): Response
    {

        $categorie = $this->repository->findOneBy(["slug"=>$slug]);


        return $this->render('categorie/categorie.html.twig',[

            "categorie" => $categorie
        ]);
    }




}

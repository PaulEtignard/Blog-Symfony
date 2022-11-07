<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $url = $adminUrlGenerator->setController(ArticleCrudController::class)
            ->generateUrl();
        //rediriger vers cette url
        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon Blog');

    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section("Article");
        //creer un sous menu pour les articles
        yield MenuItem::subMenu("Actions","fa fa-bars")
            ->setSubItems([
               MenuItem::linkToCrud("Lister Articles","fa fa-eye",Article::class)
                   ->setDefaultSort(["createdAt" => "DESC"])
                    ->setAction(Crud::PAGE_INDEX),
               MenuItem::linkToCrud("Ajouter article","fa fa-plus",Article::class)
                    ->setAction(Crud::PAGE_NEW),

            ]);

        yield MenuItem::section("Categorie");
        //creer un sous menu pour les articles
        yield MenuItem::subMenu("Actions","fa fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister Categorie","fa fa-eye",Categorie::class)
                    ->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("Ajouter Categorie","fa fa-plus",Categorie::class)
                    ->setAction(Crud::PAGE_NEW),

            ]);



        yield MenuItem::section("Contact");
        //creer un sous menu pour les articles
        yield MenuItem::subMenu("Actions","fa fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister Contact","fa fa-eye",Contact::class)
                    ->setAction(Crud::PAGE_INDEX),

            ]);
        yield MenuItem::section("Autre");
        yield MenuItem::linkToUrl("Retour au site","fa fa-close",$this->generateUrl("app_acceuil") );
    }
}

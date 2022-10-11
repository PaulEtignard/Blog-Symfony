<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Date;

class ArticleCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('contenu')
                ->setSortable(false)
                ->hideOnIndex(),
            AssociationField::new('categorie')
                ->setRequired(false),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setLabel("Date de création"),
            BooleanField::new('publie'),
            TextField::new('slug')
                ->hideOnForm(),
        ];
    }

    //redéfinition de la méthode persiste entity qui va être appelé lors de la création de l'article en base de données
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //verifier que la variable entityInstance est bien l'instance de la classe Article
        //dd($entityInstance);
        if (!$entityInstance instanceof Article) return;
        $entityInstance->setCreatedAt(new \DateTime());
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());

        //appel a ma méthode, afin de persister l'entité
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(crud::PAGE_INDEX,"Liste des articles")
            ->setPaginatorPageSize(10)
            ->setPageTitle(crud::PAGE_NEW,"Créer un article")
            ->setDefaultSort(["createdAt" =>"DESC"])
            ->setPageTitle(crud::PAGE_EDIT,"Modifier un article");

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW,
        function (Action $action){
            return $action->setLabel("Ajouter article")
                ->setIcon("fa fa-plus");
        }
        );
        $actions->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN,
            function (Action $action){
                return $action->setLabel("Valider")
                    ->setIcon("fa fa-check");
            }
        );
        $actions->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER );
        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);
        $actions->update(Crud::PAGE_INDEX,Action::DETAIL ,
            function (Action $action){
                return $action->setLabel("Detail");
            }
        );

        return $actions;

    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add("titre");
        $filters->add("createdAt");
        return $filters;
    }


}

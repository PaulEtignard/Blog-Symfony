<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    private SluggerInterface $slugger;

    //Demander a Sumfony d'injecter le slugger au niveau du constructeur
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        //Inisialisation Faker

        $faker = Factory::create("fr_FR");
        for ($i=0;$i<50;$i++){
            $article = new Article();
            $article->setTitre($faker->words($faker->numberBetween(3,10),true))
                ->setContenu($faker->paragraphs(3,true))
                ->setCreatedAt($faker->dateTimeBetween("-6 months","now"));
            $article->setSlug($this->slugger->slug($article->getTitre())->lower());

            //associer l'article a une catégorie aléatoire
            //recuperer une reference d'une catégorie
            $numcategorie = $faker->numberBetween(0,8);

            $article->setCategorie($this->getReference("categorie".$numcategorie));
            //generer l'order INSERT
            // $manager->persist($article) = $INSERT INTO article values ("titre 1 ","Contenu ..... ")
            $manager->persist($article);
        }



        //envoyer les ordres INSERT vers la base
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class
        ];
    }
}

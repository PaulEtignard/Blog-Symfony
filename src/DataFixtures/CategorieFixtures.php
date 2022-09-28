<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger=$slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<=8;$i++){
            $categorie = new Categorie();
            $categorie->setTitre($faker->word());
            $categorie->setSlug($this->slugger->slug($categorie->getTitre())->lower());
            $manager->persist($categorie);
            //créer une référence sur la catégorie
            $this->addReference("categorie".$i,$categorie);
        }

        $manager->flush();
    }
}

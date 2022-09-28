<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<=520;$i++){
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraphs(3,true));
            $commentaire->setCreatedAt($faker->dateTimeBetween("-6months"));
            $numarticle = $faker->numberBetween(0,49);
            $numutilisateur = $faker->numberBetween(0,80);
            if ($numutilisateur>50){
                $commentaire->setUtilisateur(null);
            }else{
                $commentaire->setUtilisateur($this->getReference("utilisateur".$numutilisateur));
            }


            $commentaire->setArticle($this->getReference("article".$numarticle));

            $manager->persist($commentaire);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
            UilisateurFixtures::class
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create("fr_FR");
        for ($i=0;$i<50;$i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->lastName);
            $utilisateur->setPrenom($faker->firstName);
            $utilisateur->setPseudo($faker->userName);
            $this->addReference("utilisateur".$i,$utilisateur);
            $manager->persist($utilisateur);
        }

        $manager->flush();
    }


}

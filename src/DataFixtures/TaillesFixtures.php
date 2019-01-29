<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Tailles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaillesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 5 villes! Bam!
        for ($i = 0; $i < 5; $i++) {
            $taille = new Tailles();
            $taille->setHauteurCm($i*4);
            $taille->setLargeurCm($i*2);
            $manager->persist($taille);
            $this->addReference("taille".strval($i), $taille);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SeriesFixtures::class,
            VillesFixtures::class,
            TaillesFixtures::class,
            SujetsFixtures::class,
            IndexPersonnesFixtures::class,
            IndexIconographiquesFixtures::class,
            CindocFixtures::class,
        );
    }
}
?>
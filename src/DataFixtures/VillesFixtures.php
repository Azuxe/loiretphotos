<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Villes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VillesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $array = ["Paris","Bordeaux","Lyon","Nantes","Toulouse"];

        // create 5 villes! Bam!
        for ($i = 0; $i < count($array); $i++) {
            $ville = new Villes();
            $ville->setNom($array[$i]);
            $manager->persist($ville);
            $this->addReference("ville".strval($i), $ville);
        }

        $manager->flush();
    }
}
?>
<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Sujets;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SujetsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $array = ["Eglise","Mosquée","Pont","Maison","Camping"];

        // create 5 villes! Bam!
        for ($i = 0; $i < count($array); $i++) {
            $sujets = new Sujets();
            $sujets->setSujet($array[$i]);
            $manager->persist($sujets);
            $this->addReference("sujet".strval($i), $sujets);
        }
        $manager->flush();
    }
}
?>
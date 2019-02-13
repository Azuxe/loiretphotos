<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\IndexPersonnes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IndexPersonnesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $array = ["Napoleon","Madiran","Macron","Johnny","Marc"];

        // create 5 villes! Bam!
        for ($i = 0; $i < count($array); $i++) {
            $indexper = new IndexPersonnes();
            $indexper->setIndexPersonne($array[$i]);
            $manager->persist($indexper);
            $this->addReference("IndexPer".strval($i), $indexper);
        }
        $manager->flush();
    }
}
?>
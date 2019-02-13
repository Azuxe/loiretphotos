<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\IndexIconographiques;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IndexIconographiquesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $array = ["Stele","Fleuve","Rivière","Lac","Chemin"];

        // create 5 villes! Bam!
        for ($i = 0; $i < count($array); $i++) {
            $indexico = new IndexIconographiques();
            $indexico->setIndexIco($array[$i]);
            $manager->persist($indexico);
            $this->addReference("IndexIco".strval($i), $indexico);
        }
        $manager->flush();
    }
}
?>
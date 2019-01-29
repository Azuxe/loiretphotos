<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Series;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SeriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
            $serie = new Series();
            $serie->setSerie("CVS55");
            $manager->persist($serie);
            
            $this->addReference("serie", $serie);

        $manager->flush();
    }
}
?>
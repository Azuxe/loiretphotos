<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Cindoc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CindocFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 5 villes! Bam!
        for ($i = 0; $i < 5; $i++) {
            $cindoc = new Cindoc();
            $cindoc->setCindoc("ABC".strval($i));
            $manager->persist($cindoc);
            $this->addReference("ABC".strval($i), $cindoc);
        }

        $manager->flush();
    }
}
?>
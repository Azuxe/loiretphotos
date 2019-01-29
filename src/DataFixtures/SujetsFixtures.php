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
            $sujet = new Sujets();
            $sujet->setSujet("Eglise");
            $manager->persist($sujet);
            $this->addReference("sujet", $sujet);

        $manager->flush();
    }
}
?>
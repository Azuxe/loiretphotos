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
        $index = new indexPersonnes();
        $index->setIndexPersonne("Napoleon");
        $manager->persist($index);
        $this->addReference("IndexPer", $index);

        $manager->flush();
    }
}
?>
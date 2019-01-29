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
        $index = new IndexIconographiques();
        $index->setIndexIco("Notre-Dames-D'Orléans");
        $manager->persist($index);
        $this->addReference("indexIco", $index);

        $manager->flush();
    }
}
?>
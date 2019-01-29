<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\DataFixtures\SeriesFixtures;
use App\DataFixtures\VillesFixtures;
use App\DataFixtures\TaillesFixtures;
use App\DataFixtures\SujetsFixtures;
use App\DataFixtures\IndexPersonnesFixtures;
use App\DataFixtures\IndexIconographiquesFixtures;
use App\DataFixtures\CindocFixtures;

use App\Entity\Cliches;
use App\Entity\Tailles;
use App\Entity\Sujets;
use App\Entity\Series;
use App\Entity\IndexIconographiques;
use App\Entity\IndexPersonnes;
use App\Entity\Villes;
use App\Entity\Cindoc;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ClichesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {   

        // create 10 cliches! Bam!
        for ($i = 0; $i < 5; $i++) {

            $cliches = new Cliches();

            $cliches->addCindoc($this->getReference("ABC".strval($i)));

            $cliches->setTaille($this->getReference("taille".strval($i)));
            $cliches->addSujet($this->getReference("sujet"));
            $cliches->setSerie($this->getReference("serie"));
            $cliches->addIndexIconographique($this->getReference("indexIco"));
            $cliches->addIndexPersonne($this->getReference("IndexPer"));
            $cliches->setNbCliche(1);

            $cliches->addVille($this->getReference("ville".strval($i)));

            $manager->persist($cliches);
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
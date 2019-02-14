<?php 
// src/Command/InsertDataCommand.php
namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Cindoc;
use App\Entity\Cliches;
use App\Entity\Series;
use App\Entity\Villes;
use App\Entity\Sujets;
use App\Entity\IndexPersonnes;
use App\Entity\IndexIconographiques;
use App\Entity\Tailles;







class InsertDataCommand extends ContainerAwareCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:insertdata';

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Fill the database with projet.csv')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to fill the database')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $cindoc = null;
        $em = $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $cindocrepo = $em->getRepository(Cindoc::class);
        $serierepo = $em->getRepository(Series::class);
        $villerepo = $em->getRepository(Villes::class);
        $sujetrepo = $em->getRepository(Sujets::class);
        $ipersrepo = $em->getRepository(IndexPersonnes::class);
        $iiconrepo = $em->getRepository(IndexIconographiques::class);
        $taillrepo = $em->getRepository(Tailles::class);



        $row = 0;
        $first = true;
        if (($handle = fopen("projetok.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row == 0){
                    $row++;
                    continue;
                }
                $num = count($data);
                echo "$num champs à la ligne $row\n";
                $row++;
                $cliche = new Cliches();
                for ($c=0; $c < $num; $c++) {
                    switch ($c) {
                        case 0:
                            if ($data[$c] != ""){
                               $cindoc = explode('|',$data[$c]);
                                for ($i=0; $i < count($cindoc); $i++) {
                                    $newcindoc = new Cindoc();
                                    $newcindoc->setCindoc(trim($cindoc[$i]));
                                    $oldcindoc = $cindocrepo->findOneBy(array('cindoc' => $newcindoc->getCindoc()));
                                    if(empty($oldcindoc)){
                                        $cliche->addCindoc($newcindoc);
                                    }else{
                                        $cliche->addCindoc($oldcindoc);
                                    }
                               }    
        
                            
                        }
                        break;
                        case 1:
                            if ($data[$c] != ""){
                                    $newserie = new Series();
                                    $newserie->setSerie(trim($data[$c]));
                                    $oldserie = $serierepo->findOneBy(array('serie' => $newserie->getSerie()));
                                    if(empty($oldserie)){
                                        $cliche->setSerie($newserie);
                                    }else{
                                        $cliche->setSerie($oldserie);
                                    }
                               }    
                               
                            break;
                        case 2:
                        break;
                        case 3: $cliche->setDiscriminant(trim($data[$c]));
                        break;
                        case 4:
                            if ($data[$c] != ""){
                               $villes = explode(',',$data[$c]);
                                for ($i=0; $i < count($villes); $i++) {
                                    $newville = new Villes();
                                    $newville->setNom(trim($villes[$i]));
                                    $oldville = $villerepo->findOneBy(array('nom' => $newville->getNom()));
                                    if(empty($oldville)){
                                        $cliche->addVille($newville);
                                    }else{
                                        $cliche->addVille($oldville);
                                    }
                               }    
                        }
                        case 5:
                            if ($data[$c] != ""){
                               $sujets = explode(',',$data[$c]);
                                for ($i=0; $i < count($sujets); $i++) {
                                    $newsujet = new Sujets();
                                    $newsujet->setSujet(trim($sujets[$i]));
                                    $oldsujet = $sujetrepo->findOneBy(array('sujet' => $newsujet->getSujet()));
                                    if(empty($oldsujet)){
                                        $cliche->addSujet($newsujet);
                                    }else{
                                        $cliche->addSujet($oldsujet);
                                    }
                               }    
                        }
                        break;
                        case 6: $cliche->setDescription(trim($data[$c]));
                        break;
                        case 7: $cliche->setDateDePrise(date_create($data[$c]));
                        break;
                        case 8: $cliche->setNoteDeBasDePage(trim($data[$c]));
                        break;
                        case 9: // indexperso
                            if ($data[$c] != ""){
                               $indexpersos = explode('/',$data[$c]);
                                for ($i=0; $i < count($indexpersos); $i++) {
                                    $newiperso = new IndexPersonnes();
                                    $newiperso->setIndexPersonne(trim($indexpersos[$i]));
                                    $oldiperso = $ipersrepo->findOneBy(array('indexPersonne' => $newiperso->getIndexPersonne()));
                                    if(empty($oldiperso)){
                                        $cliche->addIndexPersonne($newiperso);
                                    }else{
                                        $cliche->addIndexPersonne($oldiperso);
                                    }
                               }    
                        }
                        case 10: $cliche->setFichier(trim($data[$c]));
                        break;
                        case 11: // indexico
                            if ($data[$c] != ""){
                               $indexicos = explode('/',$data[$c]);
                                for ($i=0; $i < count($indexicos); $i++) {
                                    $newindexico = new IndexIconographiques();
                                    $newindexico->setIndexIco(trim($indexicos[$i]));
                                    $oldindexico = $iiconrepo->findOneBy(array('indexIco' => $newindexico->getIndexIco()));
                                    if(empty($oldindexico)){
                                        $cliche->addIndexIconographique($newindexico);
                                    }else{
                                        $cliche->addIndexIconographique($oldindexico);
                                    }
                               }    
                        }
                        case 12: $cliche->setNbCliche(intval($data[$c]));
                        break;
                        case 13: // indexico
                            if ($data[$c] != ""){
                                $tailles = explode('x',$data[$c]);
                                $newtaille = new Tailles();
                                $newtaille->setHauteurCm(floatval($tailles[0]));
                                $newtaille->setLargeurCm(floatval($tailles[1]));
                                $oldtaille = $taillrepo->findOneBy(array('hauteur_cm' => $newtaille->getHauteurCm(),'largeur_cm' => $newtaille->getLargeurCm()));
                                if(empty($oldtaille)){
                                    $cliche->setTaille($newtaille);
                                }else{
                                    $cliche->setTaille($oldtaille);
                                }
                        }
                        break;
                        case 14: $cliche->setSupport($data[$c]);
                        case 15: $cliche->setChroma($data[$c]);
                        case 16: $cliche->setRemarque(intval($data[$c]));
                        default:
                            # code...
                            break;
                    }
                    // foreach ($cliche->getCindoc() as $cindoc) {
                    //     echo $cindoc->getCindoc();
                    // }
                }
                $em->persist($cliche);
                $em->flush();
            }
        fclose($handle);
        }

    }
}

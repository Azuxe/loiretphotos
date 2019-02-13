<?php

namespace App\Repository;

use App\Entity\Cliches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use FOS\RestBundle\Request\ParamFetcher;


/**
 * @method Cliches|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cliches|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cliches[]    findAll()
 * @method Cliches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClichesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cliches::class);
    }

    public function findAllByParam($array){
        $qb = $this->createQueryBuilder('c');
        $qb->innerJoin('c.villes', 'villes');
        $qb->innerJoin('c.sujets', 'sujets');
        $qb->innerJoin('c.taille','tailles');
        $qb->innerJoin('c.serie', 'series');
        $qb->innerJoin('c.indexPersonnes', 'indexpersonnes');
        $qb->innerJoin('c.indexIconographiques', 'indexiconographiques');
        $qb->innerJoin('c.cindoc', 'cindoc');
             
        if(!empty($array->get('villesin'))){
            $villes = explode(':',$array->get('villesin'));
            $qb->andWhere($qb->expr()->in('villes.nom', $villes));
        }

        if(!empty($array->get('villesout'))){
            $villes = explode(':',$array->get('villesout'));
            $qb->andwhere($qb->expr()->notin('villes.nom', $villes));
        }

        if(!empty($array->get('sujetsin'))){
            $sujets = explode(':',$array->get('sujetsin'));
            $qb->andwhere($qb->expr()->in('sujets.sujet', $sujets));
        }

        if(!empty($array->get('sujetsout'))){
            $sujets = explode(':',$array->get('sujetsout'));
            $qb->andwhere($qb->expr()->notin('sujets.sujet', $sujets));
        }

        if(!empty($array->get('hauteur'))){
            $hauteur = explode(':',$array->get('hauteur'));
            $operateur = array_shift($hauteur);
            switch ($operateur) {
                case 'lt':
                    $qb->andwhere($qb->expr()->lt('tailles.hauteur_cm', $hauteur[0]));
                break;
                case 'lte':
                    $qb->andwhere($qb->expr()->lte('tailles.hauteur_cm', $hauteur[0]));
                    break;
                case 'gt':
                    $qb->andwhere($qb->expr()->gt('tailles.hauteur_cm', $hauteur[0]));
                    break;
                case 'gte':
                    $qb->andwhere($qb->expr()->gte('tailles.hauteur_cm', $hauteur[0]));
                    break;
                case 'eq':
                    $qb->andwhere($qb->expr()->eq('tailles.hauteur_cm', $hauteur[0]));
                    break;
                default:
                    $qb->andwhere($qb->expr()->eq('tailles.hauteur_cm', $hauteur[0]));
                    break;
            }
        }

        if(!empty($array->get('largeur'))){
            $largeur = explode(':',$array->get('largeur'));
            $operateur = array_shift($largeur);
            switch ($operateur) {
                case 'lt':
                    $qb->andwhere($qb->expr()->lt('tailles.largeur_cm', $largeur[0]));
                break;
                case 'lte':
                    $qb->andwhere($qb->expr()->lte('tailles.largeur_cm', $largeur[0]));
                    break;
                case 'gt':
                    $qb->andwhere($qb->expr()->gt('tailles.largeur_cm', $largeur[0]));
                    break;
                case 'gte':
                    $qb->andwhere($qb->expr()->gte('tailles.largeur_cm', $largeur[0]));
                    break;
                case 'eq':
                    $qb->andwhere($qb->expr()->eq('tailles.largeur_cm', $largeur[0]));
                    break;
                default:
                    $qb->andwhere($qb->expr()->eq('tailles.largeur_cm', $largeur[0]));
                    break;
            }

            
        }

        if(!empty($array->get('seriesin'))){
            $series = explode(':',$array->get('seriesin'));
            $qb->andwhere($qb->expr()->in('series.serie', $series));
        }

        if(!empty($array->get('seriesout'))){
            $series = explode(':',$array->get('seriesout'));
            $qb->andwhere($qb->expr()->notin('series.serie', $series));
        }

        if(!empty($array->get('indexperin'))){
            $indexPersonnes = explode(':',$array->get('indexperin'));
            $qb->andwhere($qb->expr()->in('indexpersonnes.indexPersonne', $indexPersonnes));
        }

        if(!empty($array->get('indexperout'))){
            $indexPersonnes = explode(':',$array->get('indexperout'));
            $qb->andwhere($qb->expr()->notin('indexpersonnes.indexPersonne', $indexPersonnes));//?
        }

        if(!empty($array->get('indexicoin'))){
            $indexIconographique = explode(':',$array->get('indexicoin'));
            $qb->andwhere($qb->expr()->in('indexiconographiques.indexIco', $indexIconographique));
        }

        if(!empty($array->get('indexicoout'))){
            $indexIconographique = explode(':',$array->get('indexicoout'));
            $qb->andwhere($qb->expr()->notin('indexiconographiques.indexIco', $indexIconographique));
        }

        if(!empty($array->get('cindocin'))){
            $cindoc = explode(':',$array->get('cindocin'));
            $qb->andwhere($qb->expr()->in('cindoc.cindoc', $cindoc));
        }

        if(!empty($array->get('cindocout'))){
            $cindoc = explode(':',$array->get('cindocout'));
            $qb->andwhere($qb->expr()->notin('cindoc.cindoc', $cindoc));
        }

        if(!empty($array->get('remarquein'))){
            $remarques = explode(':',$array->get('remarquein'));
            $qb->andwhere($qb->expr()->in('c.remarque', $remarques));
        }

        if(!empty($array->get('remarqueout'))){
            $remarques = explode(':',$array->get('remarqueout'));
            $qb->andwhere($qb->expr()->notin('c.remarque', $remarques));
        }

        if(!empty($array->get('basdepagein'))){
            $basdepages = explode(':',$array->get('basdepagein'));
            $qb->andwhere($qb->expr()->in('c.bas_de_page', $basdepages));
        }

        if(!empty($array->get('basdepageout'))){
            $basdepages = explode(':',$array->get('basdepageout'));
            $qb->andwhere($qb->expr()->notin('c.bas_de_page', $basdepages));
        }

        if(!empty($array->get('nbcliche'))){
            $nbcliche = explode(':',$array->get('nbcliche'));
            $operateur = array_shift($nbcliche);
            switch ($operateur) {
                case 'lt':
                    $qb->andwhere($qb->expr()->lt('c.nbCliche', $nbcliche[0]));
                break;
                case 'lte':
                    $qb->andwhere($qb->expr()->lte('c.nbCliche', $nbcliche[0]));
                    break;
                case 'gt':
                    $qb->andwhere($qb->expr()->gt('c.nbCliche', $nbcliche[0]));
                    break;
                case 'gte':
                    $qb->andwhere($qb->expr()->gte('c.nbCliche', $nbcliche[0]));
                    break;
                case 'eq':
                    $qb->andwhere($qb->expr()->eq('c.nbCliche', $nbcliche[0]));
                    break;
                default:
                    $qb->andwhere($qb->expr()->eq('c.nbCliche', $nbcliche[0]));
                    break;
            }
        }

        if(!empty($array->get('discriminantin'))){
            $discriminants = explode(':',$array->get('discriminantin'));
            $qb->andwhere($qb->expr()->in('c.discriminant', $discriminants));
        }

        if(!empty($array->get('discriminantout'))){
            $discriminants = explode(':',$array->get('discriminantout'));
            $qb->andwhere($qb->expr()->notin('c.discriminant', $discriminants));
        }

        if(!empty($array->get('chromain'))){
            $chromas = explode(':',$array->get('chromain'));
            $qb->andwhere($qb->expr()->in('c.chroma', $chromas));
        }

        if(!empty($array->get('chromaout'))){
            $chromas = explode(':',$array->get('chromaout'));
            $qb->andwhere($qb->expr()->notin('c.chroma', $chromas));
        }

        if(!empty($array->get('supportin'))){
            $supports = explode(':',$array->get('remarqueout'));
            $qb->andwhere($qb->expr()->in('c.support', $supports));
        }

        if(!empty($array->get('supportout'))){
            $supports = explode(':',$array->get('supportout'));
            $qb->andwhere($qb->expr()->notin('c.support', $supports));
        }

        if(!empty($array->get('datedeprise'))){
            $dateprise = explode(':',$array->get('datedeprise'));
            $operateur = array_shift($dateprise);
            $date_de_prise = strtotime($dateprise);
            switch ($operateur) {
                case 'lt':
                    $qb->andwhere($qb->expr()->lt('c.date_de_prise', $date_de_prise));
                break;
                case 'lte':
                    $qb->andwhere($qb->expr()->lte('c.date_de_prise', $date_de_prise));
                    break;
                case 'gt':
                    $qb->andwhere($qb->expr()->gt('c.date_de_prise', $date_de_prise));
                    break;
                case 'gte':
                    $qb->andwhere($qb->expr()->gte('c.date_de_prise', $date_de_prise));
                    break;
                case 'eq':
                    $qb->andwhere($qb->expr()->eq('c.date_de_prise', $date_de_prise));
                    break;
                case 'bt':
                    $date_de_priseint = strtotime($date_de_prise);
                    $qb->andwhere($qb->expr()->between('c.date_de_prise', $date_de_prise,$date_de_priseint));
                    break;
                default:
                    $qb->andwhere($qb->expr()->eq('c.date_de_prise', $date_de_prise));
                    break;
            }
        }

        //etc

        return $qb->getQuery()->getResult();
    }
}
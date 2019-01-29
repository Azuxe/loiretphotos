<?php

namespace App\Repository;

use App\Entity\ClichesSujets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClichesSujets|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClichesSujets|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClichesSujets[]    findAll()
 * @method ClichesSujets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClichesSujetsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClichesSujets::class);
    }

    // /**
    //  * @return ClichesSujets[] Returns an array of ClichesSujets objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClichesSujets
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

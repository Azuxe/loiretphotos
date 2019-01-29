<?php

namespace App\Repository;

use App\Entity\Cindoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cindoc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cindoc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cindoc[]    findAll()
 * @method Cindoc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CindocRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cindoc::class);
    }

    // /**
    //  * @return Cindoc[] Returns an array of Cindoc objects
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
    public function findOneBySomeField($value): ?Cindoc
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

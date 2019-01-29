<?php

namespace App\Repository;

use App\Entity\Tailles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tailles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tailles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tailles[]    findAll()
 * @method Tailles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaillesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tailles::class);
    }

    // /**
    //  * @return Tailles[] Returns an array of Tailles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tailles
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

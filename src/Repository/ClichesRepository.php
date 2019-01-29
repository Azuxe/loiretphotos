<?php

namespace App\Repository;

use App\Entity\Cliches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

    // /**
    //  * @return Cliches[] Returns an array of Cliches objects
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
    public function findOneBySomeField($value): ?Cliches
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

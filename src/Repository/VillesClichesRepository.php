<?php

namespace App\Repository;

use App\Entity\VillesCliches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VillesCliches|null find($id, $lockMode = null, $lockVersion = null)
 * @method VillesCliches|null findOneBy(array $criteria, array $orderBy = null)
 * @method VillesCliches[]    findAll()
 * @method VillesCliches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VillesClichesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VillesCliches::class);
    }

    // /**
    //  * @return VillesCliches[] Returns an array of VillesCliches objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VillesCliches
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

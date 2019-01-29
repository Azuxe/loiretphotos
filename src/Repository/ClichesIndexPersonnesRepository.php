<?php

namespace App\Repository;

use App\Entity\ClichesIndexPersonnes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClichesIndexPersonnes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClichesIndexPersonnes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClichesIndexPersonnes[]    findAll()
 * @method ClichesIndexPersonnes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClichesIndexPersonnesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClichesIndexPersonnes::class);
    }

    // /**
    //  * @return ClichesIndexPersonnes[] Returns an array of ClichesIndexPersonnes objects
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
    public function findOneBySomeField($value): ?ClichesIndexPersonnes
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

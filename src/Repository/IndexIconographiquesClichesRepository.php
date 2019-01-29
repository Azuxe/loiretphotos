<?php

namespace App\Repository;

use App\Entity\IndexIconographiquesCliches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IndexIconographiquesCliches|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexIconographiquesCliches|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexIconographiquesCliches[]    findAll()
 * @method IndexIconographiquesCliches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexIconographiquesClichesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndexIconographiquesCliches::class);
    }

    // /**
    //  * @return IndexIconographiquesCliches[] Returns an array of IndexIconographiquesCliches objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IndexIconographiquesCliches
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

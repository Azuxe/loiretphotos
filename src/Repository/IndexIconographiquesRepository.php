<?php

namespace App\Repository;

use App\Entity\IndexIconographiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IndexIconographiques|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexIconographiques|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexIconographiques[]    findAll()
 * @method IndexIconographiques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexIconographiquesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndexIconographiques::class);
    }

    // /**
    //  * @return IndexIconographiques[] Returns an array of IndexIconographiques objects
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
    public function findOneBySomeField($value): ?IndexIconographiques
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

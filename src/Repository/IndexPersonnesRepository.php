<?php

namespace App\Repository;

use App\Entity\IndexPersonnes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IndexPersonnes|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexPersonnes|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexPersonnes[]    findAll()
 * @method IndexPersonnes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexPersonnesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndexPersonnes::class);
    }

    // /**
    //  * @return IndexPersonnes[] Returns an array of IndexPersonnes objects
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
    public function findOneBySomeField($value): ?IndexPersonnes
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

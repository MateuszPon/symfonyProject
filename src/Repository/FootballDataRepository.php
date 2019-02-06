<?php

namespace App\Repository;

use App\Entity\FootballData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FootballData|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballData|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballData[]    findAll()
 * @method FootballData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FootballData::class);
    }

    // /**
    //  * @return FootballData[] Returns an array of FootballData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FootballData
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

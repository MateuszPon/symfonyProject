<?php

namespace App\Repository;

use App\Entity\Bets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bets[]    findAll()
 * @method Bets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bets::class);
    }
    public function checkBets($userId, $idMatch){
        $entityManager = $this->getEntityManager();

        $baseSQL = "Select a FROM App\Entity\Bets a where a.user='";
        $baseSQL .=$userId."'"." and a.eventMatch='".$idMatch."'";
        $query = $entityManager->createQuery($baseSQL);
        return $query->execute();

    }

    public function betsToPoints(){
        $entityManager = $this->getEntityManager();
        $baseSQL = "Select a FROM App\Entity\Bets a join App\Entity\Timetable b where a.is_check=0 and b.status='";
        $baseSQL .= "FINISHED' and a.eventMatch=b.id";
        $query = $entityManager->createQuery($baseSQL);
        return $query->execute();
    }

    public function historyBetsOfUser($user){
        $entityManager = $this->getEntityManager();
        $baseSQL = "Select a FROM App\Entity\Bets a join App\Entity\Timetable b where a.is_check=1 and b.status='";
        $baseSQL .= "FINISHED' and a.eventMatch=b.id and a.user='".$user."'";
        $query = $entityManager->createQuery($baseSQL);
        return $query->execute();
    }


    // /**
    //  * @return Bets[] Returns an array of Bets objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bets
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

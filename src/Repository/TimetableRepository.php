<?php

namespace App\Repository;

use App\Entity\Timetable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Timetable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timetable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timetable[]    findAll()
 * @method Timetable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimetableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Timetable::class);
    }

    public function findActuallyMatchday(){
        $entityManager = $this->getEntityManager();
        $status="SCHEDULED";
        $baseSQL = "Select a FROM App\Entity\Timetable a where a.status='";
        $baseSQL .= "SCHEDULED' order by a.matchday ASC";
        $query = $entityManager->createQuery($baseSQL);
        $query->setMaxResults(1);
        return $query->execute();

    }

    public function findActuallyMatches($matchday){
        $entityManager = $this->getEntityManager();
        $baseSQL = "Select a FROM App\Entity\Timetable a where a.matchday='";
        $baseSQL .= $matchday."' order by a.date ASC";
        $query = $entityManager->createQuery($baseSQL);
        return $query->execute();

    }


    //Select matchday from timetable where status="SCHEDULED" order by matchday ASC limit 1


    // /**
    //  * @return Timetable[] Returns an array of Timetable objects
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
    public function findOneBySomeField($value): ?Timetable
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

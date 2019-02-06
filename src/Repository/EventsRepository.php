<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Events::class);
    }


    public function findByFiltres($data){


        $entityManager = $this->getEntityManager();
        $baseSQL = 'SELECT e FROM App\Entity\Events e WHERE ';


        if ($data["date"]->format("Y-m-d")) {
            $baseSQL .= "e.date = '" . $data["date"]->format("Y-m-d") . "' and ";
        }

        if ($data["date"]) {
            $baseSQL .= "e.city = '" . $data["city"] . "' and ";
        }

        $baseSQL .= "e.private_status = '" . 0 . "' and ";
        if ($data["address"])
            $baseSQL .= "e.address = '" . $data["address"] . "'";

        $query = $entityManager->createQuery($baseSQL);

        return $query->execute();


    }

    public function findUserEvents($user_id){
        $entityManager = $this->getEntityManager();
        $baseSQL = 'SELECT a
        FROM App\Entity\Events a join App\Entity\Participants b where b.event=a.id and ';
        $baseSQL .= "b.user = '".$user_id."' ";
        //$baseSQL .= "ORDER BY a.date ASC";
        $query = $entityManager->createQuery($baseSQL);
        return $query->execute();

    }


    public function findUserOldEvents($user_id,$dateOld){
    $entityManager = $this->getEntityManager();
    $baseSQL = 'SELECT a
        FROM App\Entity\Events a join App\Entity\Participants b where b.event=a.id and ';
    $baseSQL .= "b.user = '".$user_id."' and a.date < '";
    $baseSQL .= $dateOld ."' ";
    $baseSQL .= "ORDER BY a.date DESC";
    $query = $entityManager->createQuery($baseSQL);
    return $query->execute();

}
    public function findUserNewEvents($user_id,$date){
        $entityManager = $this->getEntityManager();
        $baseSQL = 'SELECT a
        FROM App\Entity\Events a join App\Entity\Participants b where b.event=a.id and ';
        $baseSQL .= "b.user = '".$user_id."' and a.date >= '";
        $baseSQL .= $date ."' ";
        $baseSQL .= "ORDER BY a.date ASC";
        $query = $entityManager->createQuery($baseSQL);
        return $query->execute();

    }


    // /**
    //  * @return Events[] Returns an array of Events objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Events
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

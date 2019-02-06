<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 */

namespace App\Controller;

use App\Entity\Bets;
use App\Entity\Events;
use App\Entity\Participants;
use App\Form\Events1Type;
use App\Form\SearchEventsType;
use App\Repository\BetsRepository;
use App\Repository\EventsRepository;
use App\Repository\TimetableRepository;
use App\Repository\UserRepository;
use App\Services\FootballApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/bets")
 */
class BetsController extends AbstractController
{
    /**
     * @Route("/", name="bets_index", methods={"GET","POST"})
     */
    public function index(BetsRepository $betsRepository, UserRepository $userRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        //tablica wynikow uzytkownikow
        $users=$userRepository->findAll();
        foreach ($users as $user){
            $points=0;
            $betsOfUser=$betsRepository->findBy(['user'=>$user, 'checkTotal'=>0]);
            if($betsOfUser != null){
                foreach ($betsOfUser as $betOfUser){
                    $points=+$betOfUser->getScore();
                    $betOfUser->setCheckTotal(1);
                    $em->persist($betOfUser);
                    $em->flush();
                }
                $user->setTotalScore($points);
                $em->persist($user);
                $em->flush();
            }


        }
       $listOfUsers=$userRepository->findBy([
       ],['TotalScore'=> 'DESC']);


        return $this->render('bets/index.html.twig', [
            'listOfUsers'=>$listOfUsers,
        ]);
    }

    //to tylko dla admina - on aktualizuje wyniki

    /**
     * @Route("/points", name="points", methods={"GET","POST"})
     */
    public function points(BetsRepository $betsRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $betsToCheck = $betsRepository->betsToPoints();
        $status="Nie można zaaktualizować punktów, gdyż typowane mecze się nie zakończyły";
        if($betsToCheck != null){
            foreach ($betsToCheck as $bet) { //przeniesc pozniej do serwisu
                //prawidłowy wynik wiec 3pkt
                if (($bet->getHomeScore() == $bet->getEventMatch()->getHomeTeamScore()) && ($bet->getAwayScore() == $bet->getEventMatch()->getAwayTeamScore())) {
                    $bet->setIsCheck(1);
                    $bet->setScore(3);
                    //gdy wynik nie poprawny ale prawidlowo wskazane roztrzygniecie
                } else if ((($bet->getHomeScore() > $bet->getAwayScore() && ($bet->getEventMatch()->getHomeTeamScore() > $bet->getEventMatch()->getAwayTeamScore()))
                    || ($bet->getHomeScore() < $bet->getAwayScore() && ($bet->getEventMatch()->getHomeTeamScore() < $bet->getEventMatch()->getAwayTeamScore()))
                    || ($bet->getHomeScore() == $bet->getAwayScore() && ($bet->getEventMatch()->getHomeTeamScore() == $bet->getEventMatch()->getAwayTeamScore()))
                )){
                    $bet->setIsCheck(1);
                    $bet->setScore(1);
                }else{
                    $bet->setIsCheck(1);
                    $bet->setScore(0);
                }
                $em->persist($bet);
                $em->flush();
            }
            $status="Zaaktualiozawano puntky za dane typy";
        }


        return $this->render('bets/points.html.twig', [
            'status'=>$status,
        ]);

    }


}

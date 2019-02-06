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
use App\Services\FootballApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/timetable")
 */
class TimetableController extends AbstractController
{
    /**
     * @Route("/", name="timetable_index", methods={"GET","POST"})
     */
    public function index(TimetableRepository $timetableRepository, Request $request): Response
    {

        if( $request->getQueryString() == "status=Saved"){

          $this->addFlash(  'saveBets',
              'Your changes were saved!');
        }
        $em = $this->getDoctrine()->getManager();
        $futureMatches = $em->getRepository('App:Timetable')->findBy(['status' => 'SCHEDULED'], ['date' => 'ASC']);
        $expectedResults = $em->getRepository(Bets::class)->findBy(['user' => $this->getUser()]);
        return $this->render('timetable/index.html.twig', [
            'types' => $expectedResults,
            'matches' => $futureMatches,
        ]);
    }


    /**
     * @Route("/betMatches", name="betMatches", methods={"GET","POST"})
     */
    public function betMatches(TimetableRepository $timetableRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $numberOfMatchday = $timetableRepository->findActuallyMatchday();

        if (isset($numberOfMatchday)) {
            $numberOfMatchday = $numberOfMatchday[0]->getMatchday();
        }

        $expectedResults = $em->getRepository(Bets::class)->findBy(['user' => $this->getUser()]);

        return $this->render('timetable/betMatches.html.twig', [
            'types' => $expectedResults,
            'matches' => $timetableRepository->findActuallyMatches($numberOfMatchday),
        ]);
    }

    /**
     * @Route("/saveBetMatches", name="saveBetMatches", methods={"GET","POST"})
     */
    public function saveBetMatches(TimetableRepository $timetableRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $index = 1;
        $scoreHome = "";
        $scoreAway = "";
        foreach ($request->request as $key => $value) {
            if ($index % 2 == 1) {
                $scoreHome = $value;
            } else {
                $idMatch = (int)filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                $match = $timetableRepository->findOneBy([
                    'id' => $idMatch,
                ]);
                $scoreAway = $value;
                //czy dla tego id meczu i dla tego usera nie bylo juzo bstawienia
                $checkBets = $em->getRepository(Bets::class)->checkBets($this->getUser()->getId(), $idMatch);
                if ($checkBets != null) { //jezeli zaklad juz isttnial tego uzytkownika na mecz to edytuj jego typ
                    $checkBets[0]->setHomeScore(intval($scoreHome));
                    $checkBets[0]->setAwayScore(intval($scoreAway));
                    $em->persist($checkBets[0]);
                    $em->flush();

                } else { //jezeli zakladi nie bylo to obstaw

                    $bets = new Bets();
                    $bets->setUser($this->getUser());
                    $bets->setEventMatch($match);
                    $bets->setAwayScore($scoreAway);
                    $bets->setHomeScore($scoreHome);
                    $bets->setIsCheck(0);
                    $bets->setCheckTotal(0);
                    $em->persist($bets);
                    $em->flush();

                }

            }
            $index++;
        }
        return $this->redirectToRoute('timetable_index', ['status' => "Saved"]);
    }

    /**
     * @Route("/oldBetMatches", name="oldBetMatches", methods={"GET","POST"})
     */
    public function oldBetMatches(TimetableRepository $timetableRepository, BetsRepository $betsRepository, Request $request): Response
    {
        $oldMatches=$betsRepository->historyBetsOfUser($this->getUser()->getId());
        return $this->render('timetable/oldBetMatches.html.twig', [
        'types'=>$oldMatches,
        ]);

    }

}

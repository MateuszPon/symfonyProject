<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 */
namespace App\Controller;

use App\Entity\Events;
use App\Entity\Participants;
use App\Form\Events1Type;
use App\Form\SearchEventsType;
use App\Repository\EventsRepository;
use App\Services\FootballApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/events")
 */
class EventsController extends AbstractController
{
    /**
     * @Route("/", name="events_index", methods={"GET","POST"})
     */
    public function index(EventsRepository $eventsRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $a = null;
        $form = $this->createForm(SearchEventsType::class);
        $form->handleRequest($request);
        $participantisactive = new Participants();

        $a = $em->getRepository('App:Participants')->findBy(['user' => $this->getUser()]);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('events/index.html.twig', [
                'user_id' => $this->getUser()->getId(),
                'a' => $a,
                'form' => $form->createView(),
                'events' => $eventsRepository->findByFiltres($form->getData())]);
        }


        return $this->render('events/index.html.twig', [
            'a' => $a,
            'form' => $form->createView(),
            'events' => $eventsRepository->findAll()]);
    }

    /**
     * @Route("/myEvents", name="my_events", methods={"GET","POST"})
     */
    public function myEvents(EventsRepository $eventsRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $date= date("Y-m-d");
        $form = $this->createForm(SearchEventsType::class);
        $form->handleRequest($request);


        return $this->render('events/myEvents.html.twig', [
            'user_id' => $this->getUser()->getId(),
            'events' => $eventsRepository->findUserNewEvents($this->getUser()->getId(),$date)]);

    }
    /**
     * @Route("/myOldEvents", name="my_old_events", methods={"GET","POST"})
     */
    public function myOldEvents(EventsRepository $eventsRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $date= date("Y-m-d");
        $form = $this->createForm(SearchEventsType::class);
        $form->handleRequest($request);


        return $this->render('events/myOldEvents.html.twig', [
            'user_id' => $this->getUser()->getId(),
            'events' => $eventsRepository->findUserOldEvents($this->getUser()->getId(),$date)]);

    }
    /**
     * @Route("/myAllEvents", name="my_all_events", methods={"GET","POST"})
     */
    public function myAllEvents(EventsRepository $eventsRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $date= date("Y-m-d");
        $form = $this->createForm(SearchEventsType::class);
        $form->handleRequest($request);


        return $this->render('events/myAllEvents.html.twig', [
            'user_id' => $this->getUser()->getId(),
            'events' => $eventsRepository->findUserEvents($this->getUser()->getId())]);

    }



    /**
     * @Route("/new", name="events_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $event = new Events();
        $form = $this->createForm(Events1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $event->setAuthor($this->getUser());
            $event->setFreePlaces($event->getQuantity());
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('events_index');
        }

        return $this->render('events/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="events_show", methods="GET")
     */
    public function show(Events $event): Response
    {
        return $this->render('events/show.html.twig', ['event' => $event]);
    }


    /**
     * @Route("addtoEvents/{id}", name="addtoevents", methods="GET|POST")
     */
    public function addtoevents(Events $event): Response
    {
        $participan = new Participants();
        $participan->setEvent($event);
        $em = $this->getDoctrine()->getManager();
        $a = $em->getRepository(Participants::class)->findBy(['user' => $this->getUser(), 'event' => $event->getId()]);
        if (isset($a) && $a != null) {
            $this->addFlash(
                'notice',
                'Nie możesz się zapisać do wydarzenia, bo już bierzesz w nim udział!'
            );

        } else {
            $participan->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $event->setFreePlaces(($event->getFreePlaces() - 1));
            $em->persist($participan);
            $em->flush();
        }
        return $this->render('events/addToEvent,html.twig', ['event' => $event]);
    }

    /**
     * @Route("/{id}/edit", name="events_edit", methods="GET|POST")
     */
    public function edit(Request $request, Events $event): Response
    {
        $form = $this->createForm(Events1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('events_index', ['id' => $event->getId()]);
        }

        return $this->render('events/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="events_delete", methods="DELETE")
     */
    public function delete(Request $request, Events $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        }
        return $this->redirectToRoute('events_index');
    }

}

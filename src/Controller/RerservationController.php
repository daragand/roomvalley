<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Reservation;
use App\Service\DurationService;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RerservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(
        ReservationRepository $reservation): Response
    {
        return $this->render('reservation/reservations.html.twig', [
            'controller_name' => 'ReservationController',
            'reservation' => $reservation,
        ]);
    }

    #[Route('/room/{slug}/reservation', name: 'app_reservation_create', methods: ['GET', 'POST'])]
    public function reservation(
        ReservationRepository $reservation,
        Room $room,
        Request $request,
        DurationService $durationService,
       EntityManagerInterface $Manager
    ): Response
{
        // récupérer les dates de réservation
        $dateStart = $request->request->get('date_start');
        $dateEnd = $request->request->get('date_end');
        $nbDays = $request->request->get('nombre_jours');

        // calculer la durée de la réservation pour le prix total. mais cela ne tient pas compte du week-end. Choix de récupérer le nombre de jours calculé depuis le calendrier.
        $duration = $durationService->duration($dateStart, $dateEnd);

        // calculer le prix total de la réservation
        $totalPrice = $room->getPrice() * intval($nbDays);
        
        dump($nbDays,$totalPrice);
//conversion des dates en DateTime
        $newDateStart = new \DateTime($dateStart);
        $newDateEnd = new \DateTime($dateEnd);

        

        $resa = new Reservation();
        $resa->setRoom($room)
            ->setUsers($this->getUser())
            ->setDateStart($newDateStart)
            ->setDateEnd($newDateEnd)
            ->setDateEnd(new \DateTime())
            ->setTotalPrice($totalPrice);
            

    $Manager->persist($resa);
    dd($resa);
    $Manager->flush();
            

    
        return $this->render('reservation/reservation.html.twig', [
            'controller_name' => 'ReservationController',
            'reservation' => $reservation,
            'room' => $room,
        ]);
    }

}

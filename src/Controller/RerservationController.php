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
        ReservationRepository $reservation
    ): Response {
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
    ): Response {
        // récupérer les dates de réservation
        $dateStart = $request->request->get('date_start');
        $dateEnd = $request->request->get('date_end');
        $nbDays = $request->request->get('nombre_jours');

        // calculer la durée de la réservation pour le prix total. mais cela ne tient pas compte du week-end. Choix de récupérer le nombre de jours calculé depuis le calendrier.
        $duration = $durationService->duration($dateStart, $dateEnd);

        //vérification que la durée de la réservation est supérieure à 0. Cela inclus également que la date de fin est supérieure à la date de début.
        if (intval($nbDays) == 0) {
            $this->addFlash(
                'errorResa',
                'La durée de votre réservation doit être supérieure à 0 jour(s)'
            );
            return $this->redirect($request->headers->get('referer'));
        }
        //conversion des dates en DateTime
        $newDateStart = new \DateTime($dateStart);
        $newDateEnd = new \DateTime($dateEnd);

        // vérifier que la date de début est bien antérieure à la date de fin

        // calculer le prix total de la réservation
        $totalPrice = $room->getPrice() * intval($nbDays);






        $resa = new Reservation();
        $resa->setRoom($room)
            ->setUsers($this->getUser())
            ->setDateStart($newDateStart)
            ->setDateEnd($newDateEnd)
            ->setDateEnd(new \DateTime())
            ->setTotalPrice($totalPrice);


        $Manager->persist($resa);

        $Manager->flush();

        $query = $Manager->createQuery(
            'SELECT r
            FROM App\Entity\Reservation r
            WHERE r.users = :user
            ORDER BY r.id DESC'
        )->setParameter('user', $this->getUser())
            ->setMaxResults(1)
            ->getResult();


        // afficher un message de confirmation
        // dd($query   );

        $latestReservation = $query[0];
        if ($latestReservation) {
            $dateDebut = $latestReservation->getDateStart();
            $dateFin = $latestReservation->getDateEnd();
            $roomName= $latestReservation->getRoom()->getName();
            $message = 'Votre réservation pour la salle <strong>'.$roomName.'</strong> du ' . $dateDebut ->format('d-m-Y') . ' au '.$dateFin ->format('d-m-Y').' a bien été prise en compte.';
            $this->addFlash('successResa', $message);
        }

        // dd($query);
        return $this->render('page/room_show.html.twig', [
            'controller_name' => 'PageController',
            'room' => $room,
            'resa'=>$query
        ]);
    }
}

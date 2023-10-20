<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Service\DurationService;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class RerservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(
        ReservationRepository $reservation,
        UserInterface $user,
    ): Response {
        return $this->render('reservation/reservations.html.twig', [
            'controller_name' => 'ReservationController',
            'reservation' => $reservation,
            'user' => $user
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
          // récupération des réservations liées à la salle pour le calendrier
          $resList = $room->getReservations();
        // Récupération des données envoyées par le formulaire
        $dateStart = $request->request->get('date_start');
        $dateEnd = $request->request->get('date_end');
        $nbDays = $request->request->get('nombre_jours');
    
        // Utilisation du service pour calculer la durée entre les deux dates
        $duration = $durationService->duration($dateStart, $dateEnd);
    
        // Si la durée est nulle, ajouter un message d'erreur et rediriger l'utilisateur
        // if (intval($nbDays) == 0) {
        //     $this->addFlash('errorResa', 'La durée de votre réservation doit être supérieure à 0 jour(s)');
        //     return $this->redirect($request->headers->get('referer'));
        // }
    
        // Conversion des dates string en objets DateTime pour une manipulation plus aisée
        $newDateStart = new \DateTime($dateStart);
        $newDateEnd = new \DateTime($dateEnd);
    
        // Vérifier que la date de début est bien avant la date de fin
        if ($newDateStart >= $newDateEnd) {
            $this->addFlash('errorResa', 'La date de début doit être antérieure à la date de fin.');
            return $this->redirect($request->headers->get('referer'));
        }
    
        // Calculer le coût total de la réservation
        $totalPrice = $room->getPrice() * intval($nbDays);
    
        // Vérifier si la salle est déjà réservée pour les dates spécifiées
        $existingReservations = $reservation->findOverlappingReservations($room, $newDateStart, $newDateEnd);
    
        if (count($existingReservations) > 0) {
            // Si des réservations conflictuelles existent, informer l'utilisateur avec un message d'erreur
            $firstConflictReservation = $existingReservations[0];
            $conflictStartDate = $firstConflictReservation->getDateStart()->format('d-m-Y');
            $conflictEndDate = $firstConflictReservation->getDateEnd()->format('d-m-Y');
            $conflictRoomName = $firstConflictReservation->getRoom()->getName();
    
            $errorMessage = "Désolé, la salle " . $conflictRoomName . " est déjà réservée du " . $conflictStartDate . " au " . $conflictEndDate . ". Veuillez choisir une autre date ou salle.";
            $this->addFlash('errorResa', $errorMessage);
    
            return $this->render('page/room_show.html.twig', [
                'controller_name' => 'PageController',
                'room' => $room,
                'errors' => [],
                'resa' => null,
                'reslist' => $resList,

            ]);
        } else {
            // Si aucune réservation conflictuelle n'est trouvée, créer la nouvelle réservation
            $resa = new Reservation();
            $resa->setRoom($room)
                ->setUsers($this->getUser())
                ->setDateStart($newDateStart)
                ->setDateEnd($newDateEnd)
                ->setTotalPrice($totalPrice);
    
            $Manager->persist($resa);
            $Manager->flush();
    
            // Informer l'utilisateur que la réservation a bien été effectuée
            $query = $Manager->createQuery(
                'SELECT r
                FROM App\Entity\Reservation r
                WHERE r.users = :user
                ORDER BY r.id DESC'
            )
                ->setParameter('user', $this->getUser())
                ->setMaxResults(1)
                ->getResult();
    
            $latestReservation = $query[0];
            if ($latestReservation) {
                $dateDebut = $latestReservation->getDateStart();
                $dateFin = $latestReservation->getDateEnd();
                $roomName = $latestReservation->getRoom()->getName();
                $message = 'Votre réservation pour la salle ' . $roomName . ' du ' . $dateDebut->format('d-m-Y') . ' au ' . $dateFin->format('d-m-Y') . ' a bien été prise en compte.';
                $this->addFlash('successResa', $message);
            }
    
            return $this->render('page/room_show.html.twig', [
                'controller_name' => 'PageController',
                'room' => $room,
                'errors' => [],
                'resa' => $query,
                'reslist' => $resList,
            ]);
        }
    }
    
#[Route('/reservation/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $em): Response
{
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

        $this->addFlash('success', 'La réservation a été modifiée avec succès.');

        return $this->redirectToRoute('app_reservation', ['id' => $reservation->getId()]);
    }

    return $this->render('reservation/reservation_edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form->createView(),
    ]);
}



#[Route('/reservation/{id}/delete', name: 'app_reservation_delete', methods: ['POST'])]


public function delete(Request $request, Reservation $reservation, EntityManagerInterface $em): Response {
    $em->remove($reservation);
    $em->flush();

    $this->addFlash('success', 'La réservation a été supprimée avec succès.');

    return $this->redirectToRoute('app_reservation');
}


}

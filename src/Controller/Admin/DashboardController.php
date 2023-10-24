<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Ergonomy;
use App\Entity\Software;
use App\Entity\Equipment;
use App\Entity\ImagesRoom;
use App\Entity\Reservation;
use App\Entity\TypeEquipment;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;



class DashboardController extends AbstractDashboardController
{
   
    public function __construct(private ReservationRepository $reservationRepository, private EntityManagerInterface $entityManager)
    {

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $currentDate = new \DateTime();
        $allReservations = $this->reservationRepository->findAll();
    
        $urgentReservations = [];
        foreach ($allReservations as $reservation) {
            if ($reservation->getDateStart()->diff($currentDate)->days <= 5 && !$reservation->isIsConfirmed()) {
                $urgentReservations[] = $reservation;
            }
        }
    
        return $this->render('admin/dashboard.html.twig', [
            'reservations' => $allReservations,
            'urgentReservations' => $urgentReservations
        ]);
    }
    
    
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        
        ->setTitle('Administration')
        ->renderContentMaximized();
    }
    
    #[Route('/admin/reservation/{id}/confirm', name: 'admin_reservation_confirm')]
  
    public function confirmReservation(Reservation $reservation, EntityManagerInterface $entityManager)
    {

        $reservation->confirm();
        $entityManager->flush();
        return $this->redirectToRoute('admin');
    }


    #[Route('/admin/reservation/{id}/cancel', name: 'admin_reservation_cancel')]

public function deleteReservationRequest(int $id, EntityManagerInterface $entityManager)
{
    // Trouver la demande de réservation par son ID
    $reservation = $entityManager->getRepository(Reservation::class)->find($id);

    if (!$reservation) {
        // Gérer le cas où la demande de réservation n'existe pas, peut-être rediriger avec un message d'erreur.
        throw $this->createNotFoundException('Pas de reservation liée à cet id '.$id);
    }

    // Supprimer la demande de réservation de la base de données
    $entityManager->remove($reservation);
    $entityManager->flush();

    // Rediriger vers la page appropriée avec un message flash
    $this->addFlash('success', 'La réservation a bien été annulée');
    return $this->redirectToRoute('admin'); // Rediriger vers la page d'administration
}



    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard("Panneau d'administration", 'fa fa-home');
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        yield MenuItem::section('Gestion des salles');
        yield MenuItem::linkToCrud('Réservations', 'fa-solid fa-user-tie', Reservation::class);
        yield MenuItem::linkToCrud('Salles', 'fa-brands fa-codepen', Room::class);
        yield MenuItem::linkToCrud('Adresses', 'fa-solid fa-location-dot', Address::class);
        yield MenuItem::linkToCrud('Images des salles', 'fa-solid fa-images', ImagesRoom::class);
        yield MenuItem::section('Logistiques');
        yield MenuItem::linkToCrud('Équipements', 'fa-solid fa-toolbox', Equipment::class);
        yield MenuItem::linkToCrud("Type d'équipements", 'fa-solid fa-icons', TypeEquipment::class);
        yield MenuItem::linkToCrud('Ergonomies', 'fa-solid fa-wheelchair', Ergonomy::class);
        yield MenuItem::linkToCrud('Logiciels', 'fa-solid fa-laptop-code', Software::class);
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-arrow-left', 'app_page');
    }
    
}

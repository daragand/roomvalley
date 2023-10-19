<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Equipment;
use App\Entity\Ergonomy;
use App\Entity\ImagesRoom;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\Software;
use App\Entity\Status;
use App\Entity\TypeEquipment;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;



class DashboardController extends AbstractDashboardController
{
   
    public function __construct(private ReservationRepository $reservationRepository)
    {

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
               
        $reservations = $this->reservationRepository->findAll();
                return $this->render('admin/dashboard.html.twig', [
                   'reservation' => $reservations,
                ]);
            }

        
        
    

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()

            ->setTitle('Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard("Panneau d'administration", 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        yield MenuItem::linkToCrud('Adresses', 'fa-solid fa-location-dot', Address::class);
        yield MenuItem::linkToCrud('Équipements', 'fa-solid fa-toolbox', Equipment::class);
        yield MenuItem::linkToCrud('Ergonomies', 'fa-solid fa-wheelchair', Ergonomy::class);
        yield MenuItem::linkToCrud('Réservations', 'fa-solid fa-user-tie', Reservation::class);
        yield MenuItem::linkToCrud('Salles', 'fa-solid fa-mug-saucer', Room::class);
        yield MenuItem::linkToCrud('Logiciels', 'fa-solid fa-laptop-code', Software::class);
        yield MenuItem::linkToCrud('Statuts', 'fa-solid fa-sitemap', Status::class);
        yield MenuItem::linkToCrud("Type d'équipements", 'fa-solid fa-icons', TypeEquipment::class);
    }
}

<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page')]
    public function index(RoomRepository $roomRepository ): Response
    {
        $rooms = $roomRepository->findAll();
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
            'rooms' => $rooms,
            
        ]);
    }

    #[Route('/room/{slug}', name: 'app_room_show')]
    public function room(
        Room $room,
    ): Response
    {
        return $this->render('page/room_show.html.twig', [
            'controller_name' => 'PageController',
            'room' => $room,
        ]);
    }
}

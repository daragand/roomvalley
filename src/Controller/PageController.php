<?php

namespace App\Controller;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page')]
    public function index(
        
    ): Response
    {
        return $this->render('page/room.html.twig', [
            'controller_name' => 'PageController',
            
        ]);
    }

    #[Route('/room/{slug}', name: 'app_room_show')]
    public function room(
        Room $room,
    ): Response
    {
        return $this->render('page/room_show.html.twig', [
            'controller_name' => 'PageController',
            'room' => $room
        ]);
    }
}

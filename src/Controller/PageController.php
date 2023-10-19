<?php

namespace App\Controller;

use App\Entity\Room;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    #[Route('/contact', name: 'contact')]
    public function contact(
        Request $request,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $subject = $form->get('subject')->getData();
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $email = $form->get('email')->getData();
            $phone = $form->get('phone')->getData();
            $message = $form->get('message')->getData();

            // On paramètre le mail
            $mail = (new Email())
                ->from($email)
                ->to('contact@wecine.fr')
                ->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->html(
                    '<div> Client : ' . $firstname . ' ' . $lastname . '<br>
                    Numéro de téléphone : ' . $phone . ' <br>
                    Message :<br>' . $message . '</div>'
                );

            //On envoie le mail
            $mailer->send($mail);

            // On affiche un message de confirmation
            $this->addFlash('success', 'Votre message a bien été envoyé !');
        }
        return $this->render('page/contact.html.twig', [
            'title' => 'Nous contacter',
            'description' => 'Formulaire de contact de la plateforme WeCiné',
            'contact' => $form,
        ]);
    }
}

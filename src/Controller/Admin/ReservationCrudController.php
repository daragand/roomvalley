<?php

namespace App\Controller\Admin;

use App\Entity\ImagesRoom;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Controller\Admin\ImagesRoomCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Réservations')
            ->setEntityLabelInSingular('Réservation')
            ->setPageTitle('index','Gestion des réservations')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom de l\'utilisateur')
                ->setIcon('fa-solid fa-user')
                ->setHelp('Saisissez le nom de l\'utilisateur'),
            TextField::new('users', 'Nom de l\'utilisateur'),

            FormField::addPanel('Nom de la salle')
                ->setIcon('fa-solid fa-mug-saucer')
                ->setHelp('Saisissez le nom de la salle'),
            TextField::new('room', 'Nom de la salle'),

            FormField::addPanel('Date de début')
                ->setIcon('fa-solid fa-calendar-days')
                ->setHelp('Saisissez la date de début de la réservation'),
            DateField::new('date_start', 'Date de début'),

            FormField::addPanel('Date de fin')
            ->setIcon('fa-solid fa-calendar-days')
            ->setHelp('Saisissez la date de fin de la réservation'),
            DateField::new('date_end', 'Date de fin'),

            FormField::addPanel('Prix de la salle')
                ->setIcon('fa-solid fa-euro-sign')
                ->setHelp('Saisissez le prix de la salle'),
            NumberField::new('total_price', 'Prix de la salle'),

            // AssociationField::new('imagesRoom', 'Image de la réservation')
            //     ->setCrudController(ImagesRoomCrudController::class),

            BooleanField::new('is_confirmed', 'Confirmé(e)'),
        ];
    }
}

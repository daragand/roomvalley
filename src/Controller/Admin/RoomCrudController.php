<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Controller\Admin\AddressCrudController;
use App\Controller\Admin\ErgonomyCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Controller\Admin\EquipmentRoomQuantityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom de la salle')
                ->setIcon('fa-solid fa-mug-saucer')
                ->setHelp('Saisissez le nom de la salle'),
            TextField::new('name', 'Nom de la salle'),

            AssociationField::new('address', 'Adresse')
                ->setCrudController(AddressCrudController::class),

            FormField::addPanel('Capacité de la salle')
                ->setHelp('Saisissez la capacité de la salle'),
            NumberField::new('capacity', 'Capacité de la salle'),

            FormField::addPanel('Description de la salle')
                ->setHelp('Écrivez une description'),
            TextareaField::new('description', 'Description de la salle'),

            FormField::addPanel('Prix de la salle')
                ->setHelp("Saisissez le prix de la salle"),
            NumberField::new('price', 'Prix de la salle'),

            FormField::addPanel('Choix de l\'équipement')
                ->setHelp("Choisissez vos équipements"),
            AssociationField::new('equipmentRoomQuantities', 'Choix de l\'équipement')
                ->setCrudController(EquipmentRoomQuantityCrudController::class),

            FormField::addPanel('Choix de l\'ergonomie')
                ->setHelp("Choisissez l'ergonomie"),
            AssociationField::new('ergonomy', 'Choix de l\'ergonomie')
                ->setCrudController(ErgonomyCrudController::class),
        ];
    }
}

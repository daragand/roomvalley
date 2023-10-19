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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Salles')
            ->setEntityLabelInSingular('Salle')
            ->setPageTitle('index','Gestion des salles')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom de la salle')
                ->setIcon('fa-solid fa-mug-saucer')
                ->setHelp('Saisissez le nom de la salle'),
            TextField::new('name', 'Nom de la salle'),

            FormField::addPanel('Adresse de la salle')
                ->setIcon('fa-solid fa-location-dot'),
            AssociationField::new('address', 'Adresse de la salle')
                ->setCrudController(AddressCrudController::class),

            FormField::addPanel('Capacité de la salle')
                ->setIcon('fa-solid fa-warehouse')
                ->setHelp('Saisissez la capacité de la salle'),
            NumberField::new('capacity', 'Capacité de la salle'),

            FormField::addPanel('Description de la salle')
                ->setIcon('fa-solid fa-receipt')
                ->setHelp('Écrivez une description'),
            TextareaField::new('description', 'Description de la salle'),

            FormField::addPanel('Prix de la salle')
                ->setIcon('fa-solid fa-euro-sign')
                ->setHelp("Saisissez le prix de la salle"),
            NumberField::new('price', 'Prix de la salle'),

            FormField::addPanel('Choix de l\'équipements')
                ->setIcon('fa-solid fa-toolbox')
                ->setHelp("Choisissez vos équipements"),
            AssociationField::new('equipmentRoomQuantities', 'Choix de l\'équipements')
                ->setCrudController(EquipmentRoomQuantityCrudController::class),

            FormField::addPanel('Choix de l\'ergonomie')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp("Choisissez l'ergonomie"),
            AssociationField::new('ergonomy', 'Choix de l\'ergonomie')
                ->setCrudController(ErgonomyCrudController::class),
        ];
    }
}

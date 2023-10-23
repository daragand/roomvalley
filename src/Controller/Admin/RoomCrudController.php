<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\ImagesRoom;
use App\Controller\Admin\AddressCrudController;
use App\Controller\Admin\ErgonomyCrudController;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Controller\Admin\EquipmentRoomQuantityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\ImageConfigurator;
use Symfony\Component\Form\Extension\Core\DataTransformer\UlidToStringTransformer;

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
            ->setPageTitle('index','Gestion des salles');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom de la salle')
                ->setIcon('fa-solid fa-mug-saucer')
                ->setHelp('Saisissez le nom de la salle'),
            TextField::new('name', 'Nom de la salle'),

            // Le champ ImageField a été supprimé ici, comme demandé

            FormField::addPanel('Adresse de la salle')
                ->setIcon('fa-solid fa-location-dot'),
            AssociationField::new('address', 'Adresse de la salle')
                ->setCrudController(AddressCrudController::class),

            FormField::addPanel('Capacité de la salle')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp("Choisissez la capacité de la salle"),
            NumberField::new('capacityMin', 'Capacité minimum de la salle'),
            NumberField::new('capacity', 'Capacité maximum de la salle'),

            FormField::addPanel('Status de la salle')
            ->setIcon('fa-solid fa-wheelchair')
            ->setHelp("Choisissez la capacité de la salle"),
        AssociationField::new('status', 'Status actuel de la salle'),

            FormField::addPanel('Description de la salle')
                ->setIcon('fa-solid fa-receipt')
                ->setHelp('Écrivez une description'),
            TextareaField::new('description', 'Description de la salle')
            ->hideOnIndex(),

            FormField::addPanel('Prix de la salle')
                ->setIcon('fa-solid fa-euro-sign')
                ->setHelp("Saisissez le prix de la salle"),
            NumberField::new('price', 'Prix de la salle'),

            FormField::addPanel('Choix de l\'équipements')
                ->setIcon('fa-solid fa-toolbox')
                ->setHelp("Choisissez vos équipements"),
            AssociationField::new('equipmentRoomQuantities', 'Choix de l\'équipements')
                ->setCrudController(EquipmentRoomQuantityCrudController::class)
                ->hideOnIndex(),

            FormField::addPanel('Choix de l\'ergonomie')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp("Choisissez l'ergonomie"),
            AssociationField::new('ergonomy', 'Choix de l\'ergonomie')
                ->setCrudController(ErgonomyCrudController::class)
                ->hideOnIndex(),
        ];
    }

    public function new(AdminContext $context)
{
    $response = parent::new($context);
    
    if ($response instanceof RedirectResponse && $context->getEntity()->getInstance() instanceof Room) {
        // Récupérez la salle nouvellement créée
        $newlyCreatedRoom = $context->getEntity()->getInstance();
        
        return $this->redirectToRoute('admin', [
            'crudAction' => 'new',
            'crudControllerFqcn' => ImagesRoomCrudController::class,
            'roomId' => $newlyCreatedRoom->getId(),
        ]);
    }
    
    return $response;
}
    
}

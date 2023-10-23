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

    // Configure les actions (boutons) disponibles pour cette entité
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, Action::DETAIL); // Ajoute un bouton "Détails" sur la page d'index
    }

    // Configure le comportement global du CRUD pour cette entité
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Salles') // Libellé au pluriel pour la page d'index
            ->setEntityLabelInSingular('Salle') // Libellé au singulier pour la page d'index
            ->setPageTitle('index','Gestion des salles'); // Titre de la page d'index
    }

    // Configure les champs du formulaire d'édition et de création
    public function configureFields(string $pageName): iterable
    {
        return [
            // Champ "Nom de la salle" avec un panel, une icône et une aide
            FormField::addPanel('Nom de la salle')
                ->setIcon('fa-solid fa-mug-saucer')
                ->setHelp('Saisissez le nom de la salle'),
            TextField::new('name', 'Nom de la salle'), // Champ de texte pour le nom de la salle

            // Le champ ImageField a été supprimé ici, comme demandé

            // Champ "Adresse de la salle" avec panel, icône et association vers l'entité Adresse
            FormField::addPanel('Adresse de la salle')
                ->setIcon('fa-solid fa-location-dot'),
            AssociationField::new('address', 'Adresse de la salle')
                ->setCrudController(AddressCrudController::class),

            // Champ "Capacité de la salle" avec panel, icône et aide
            FormField::addPanel('Capacité de la salle')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp("Choisissez la capacité de la salle"),
            NumberField::new('capacityMin', 'Capacité minimum de la salle'), // Champ de nombre pour la capacité minimum
            NumberField::new('capacity', 'Capacité maximum de la salle'), // Champ de nombre pour la capacité maximum

            // Champ "Status de la salle" avec panel, icône et association vers l'entité Status
            FormField::addPanel('Status de la salle')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp("Choisissez la capacité de la salle"),
            AssociationField::new('status', 'Status actuel de la salle'),

            // Champ "Description de la salle" avec panel, icône et aide
            FormField::addPanel('Description de la salle')
                ->setIcon('fa-solid fa-receipt')
                ->setHelp('Écrivez une description'),
            TextareaField::new('description', 'Description de la salle')
                ->hideOnIndex(), // Champ de texte multiligne pour la description (masqué dans la liste)

            // Champ "Prix de la salle" avec panel, icône et aide
            FormField::addPanel('Prix de la salle')
                ->setIcon('fa-solid fa-euro-sign')
                ->setHelp("Saisissez le prix de la salle"),
            NumberField::new('price', 'Prix de la salle'), // Champ de nombre pour le prix

            // Champ "Choix de l'équipements" avec panel, icône et association vers l'entité EquipmentRoomQuantity
            FormField::addPanel('Choix de l\'équipements')
                ->setIcon('fa-solid fa-toolbox')
                ->setHelp("Choisissez vos équipements"),
            AssociationField::new('equipmentRoomQuantities', 'Choix de l\'équipements')
                ->setCrudController(EquipmentRoomQuantityCrudController::class)
                ->hideOnIndex(), // Champ d'association vers les équipements (masqué dans la liste)

            // Champ "Choix de l'ergonomie" avec panel, icône et association vers l'entité Ergonomie
            FormField::addPanel('Choix de l\'ergonomie')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp("Choisissez l'ergonomie"),
            AssociationField::new('ergonomy', 'Choix de l\'ergonomie')
                ->setCrudController(ErgonomyCrudController::class)
                ->hideOnIndex(), // Champ d'association vers l'ergonomie (masqué dans la liste)
        ];
    }

    // Surcharge de la méthode "new" pour rediriger vers le formulaire de création d'ImagesRoom après la création de Room
    public function new(AdminContext $context)
    {
        $response = parent::new($context);
    
        if ($response instanceof RedirectResponse && $context->getEntity()->getInstance() instanceof Room) {
            // Récupère la salle nouvellement créée
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
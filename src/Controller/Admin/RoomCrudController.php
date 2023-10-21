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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
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

            ImageField::new('imagesRooms', 'Photo de la salle')
    ->setBasePath('uploads/')
    ->setUploadDir('public/uploads')
    ->setFormTypeOptions([
        'mapped' => false,
        'multiple' => true,
        'empty_data'   => [],
        'required' => true,
        'attr' => ['accept' => 'image/*'],
    ])
    ->setUploadDir('/public/uploads')
    ->setUploadedFileNamePattern('[randomhash].[extension]')
    ->setRequired(true)
    ->setCustomOption('move', false)
    ->setCustomOption('fileName', function (UploadedFile $uploadedFile, array $context) {
         'custom-name.' . $uploadedFile->guessExtension();

    })
    ->onlyOnForms(),

    // ImageField::new('imagesRooms', 'Chemin de l\'image')
    // ->setBasePath('uploads/')
    // ->setUploadDir('public/uploads')
    // ->setUploadedFileNamePattern(
    //     fn (UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension()))
    // ->onlyOnForms(),

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
        AssociationField::new('status', 'Status actuel de la salle')
            ,
        
       


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
}

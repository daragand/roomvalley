<?php

namespace App\Controller\Admin;

use App\Entity\ImagesRoom;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImagesRoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImagesRoom::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Images room')
            ->setEntityLabelInSingular('Image room')
            ->setPageTitle('index', "Gestion d'images des salles")
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Salle')
                ->setIcon('fa-brands fa-codepen')
                ->setHelp('Salle'),
            AssociationField::new('room', 'Salle'),

            FormField::addPanel('Chemin de l\'image')
                ->setIcon('fa-solid fa-image')
                ->setHelp('Saisissez le chemin d\'accès pour l\'image'),
            ImageField::new('path', 'Chemin de l\'image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern(
                    fn (UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension()))
                
        ];
    }
}

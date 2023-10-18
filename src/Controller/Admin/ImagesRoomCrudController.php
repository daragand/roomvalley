<?php

namespace App\Controller\Admin;

use App\Entity\ImagesRoom;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
            IdField::new('id'),
            TextField::new('room_id'),
            TextField::new('path'),
        ];
    }
}

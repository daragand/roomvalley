<?php

namespace App\Controller\Admin;

use App\Entity\TypeEquipment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeEquipmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeEquipment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural("Type d'équipements")
            ->setEntityLabelInSingular("Type d'équipement")
            ->setPageTitle('index', 'Gestion des équipements')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom du type d\'équipement')
                ->setIcon('fa-solid fa-n')
                ->setHelp('Saisissez le nom du type d\'équipement'),
            TextField::new('name', 'Nom du type d\'équipement'),

            FormField::addPanel('Choix de l\'icône')
                ->setIcon('fa-solid fa-i')
                ->setHelp('Choisissez un icône'),
            TextField::new('icon', 'Choix de l\'icône'),
        ];
    }
}

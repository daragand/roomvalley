<?php

namespace App\Controller\Admin;

use App\Entity\TypeEquipment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('icon'),
        ];
    }
}

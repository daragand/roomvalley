<?php

namespace App\Controller\Admin;

use App\Entity\Software;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SoftwareCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Software::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Logiciels')
            ->setEntityLabelInSingular('Logiciel')
            ->setPageTitle('index','Gestion des logiciels')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom du logiciel')
                ->setIcon('fa-solid fa-laptop-code')
                ->setHelp('Saisissez le nom du logiciel'),
            TextField::new('name', 'Nom du logiciel'),

            FormField::addPanel('Version du logiciel')
                ->setIcon('fa-solid fa-laptop-code')
                ->setHelp('Saisissez la version du logiciel'),
            TextField::new('version', 'Version du logiciel'),
        ];
    }

}

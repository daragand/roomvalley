<?php

namespace App\Controller\Admin;

use App\Entity\Ergonomy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ErgonomyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ergonomy::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Ergonomies')
            ->setEntityLabelInSingular('Ergonomie')
            ->setPageTitle('index', "Gestion de l'ergonomie")
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Nom du moyen d\'ergonomie')
                ->setIcon('fa-solid fa-wheelchair')
                ->setHelp('Saisissez votre moyen d\'ergonomie'),
            TextField::new('name', 'Nom du moyen d\'ergonomie'),

            FormField::addPanel('Description du moyen d\'ergonomie')
                ->setIcon('fa-solid fa-receipt')
                ->setHelp('Écrivez une description'),
            TextareaField::new('description', 'Description du moyen d\'ergonomie'),

            FormField::addPanel('Icône d\'ergonomie')
                ->setIcon('fa-solid fa-i')
                ->setHelp('Choisissez votre icône d\'ergonomie'),
            TextField::new('icon', 'Icône d\'ergonomie'),
        ];
    }
}

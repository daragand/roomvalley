<?php

namespace App\Controller\Admin;


use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Adresses')
            ->setEntityLabelInSingular('Adresse')
            ->setPageTitle('index','Gestion des adresses')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Adresse de la salle')
                ->setIcon('fa-solid fa-location-dot')
                ->setHelp('Saisissez l\'adresse de la salle'),
            TextField::new('Address', 'Adresse de la salle'),

            FormField::addPanel('Code postal')
                ->setIcon('fa-solid fa-location-pin')
                ->setHelp('Saisissez le code postal de la salle'),
            TextField::new('zip', 'Code postal'),

            FormField::addPanel('Ville')
                ->setIcon('fa-solid fa-city')
                ->setHelp('Saisissez la ville'),
            TextField::new('city', 'Ville'),
            
            FormField::addPanel('Étage')
                ->setIcon('fa-solid fa-city')
                ->setHelp('Saisissez l\'étage'),
            NumberField::new('floor', 'Étage'),
        ];
    }
}

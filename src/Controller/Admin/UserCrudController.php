<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('utilisateur')
            ->setPageTitle("index", "Gestion des utilisateurs")
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Adresse de l\'utilisateur')
                ->setIcon('fa-solid fa-location-dot')
                ->setHelp('Saisissez l\'adresse de l\'utilisateur'),
            TextField::new('address', 'Adresse de l\'utilisateur'),

            FormField::addPanel('Email de l\'utilisateur')
                ->setIcon('fa-solid fa-at')
                ->setHelp('Saisissez l\'email de l\'utilisateur'),
            TextField::new('email', 'Email de l\'utilisateur'),

            FormField::addPanel('Rôle de l\'utilisateur')
                ->setIcon('fa-solid fa-star')
                ->setHelp('Saisissez le rôle de l\'utilisateur'),
            ArrayField::new('roles', 'Rôle de l\'utilisateur'),

            FormField::addPanel('Prénom de l\'utilisateur')
                ->setIcon('fa-solid fa-p')
                ->setHelp('Saisissez le prénom de l\'utilisateur'),
            TextField::new('firstname', 'Prénom de l\'utilisateur'),
            
            FormField::addPanel('Nom de l\'utilisateur')
                ->setIcon('fa-solid fa-n')
                ->setHelp('Saisissez le nom de l\'utilisateur'),
            TextField::new('lastname', 'Nom de l\'utilisateur'),

            FormField::addPanel('Numéro de téléphone')
                ->setIcon('fa-solid fa-phone')
                ->setHelp('Saisissez le numéro de téléphone'),
            TextField::new('phone', 'Numéro de téléphone'),
        ];
    }
}

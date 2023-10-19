<?php

namespace App\Controller\Admin;

use App\Entity\Equipment;
use App\Controller\Admin\SoftwareCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\TypeEquipmentCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class EquipmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Équipements')
            ->setEntityLabelInSingular('équipement')
            ->setPageTitle("index", "Gestion des équipements")
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Type d\'équipement')
                ->setIcon('fa-solid fa-icons')
                ->setHelp('Choisissez le type d\'équipement'),
            AssociationField::new('type')
                ->setCrudController(TypeEquipmentCrudController::class),

            FormField::addPanel('Logiciel de l\'équipement')
                ->setIcon('fa-solid fa-laptop-code')
                ->setHelp('Choisissez le logiciel de l\'équipement'),
            AssociationField::new('software', 'Logiciel de l\'équipement')
                ->setCrudController(SoftwareCrudController::class),

            FormField::addPanel('Nom de l\'équipement')
                ->setIcon('fa-solid fa-n')
                ->setHelp('Saisissez le nom de l\'équipement'),
            TextField::new('name', 'Nom de l\'équipement'),

            FormField::addPanel('Description de l\'équipement')
                ->setIcon('fa-solid fa-receipt')
                ->setHelp('Écrivez une description'),
            TextareaField::new('description', 'Description de l\'équipement'),
        ];
    }
}

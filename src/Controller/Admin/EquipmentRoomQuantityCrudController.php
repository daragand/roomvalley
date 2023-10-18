<?php

namespace App\Controller\Admin;

use App\Entity\EquipmentRoomQuantity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipmentRoomQuantityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EquipmentRoomQuantity::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

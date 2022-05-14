<?php

namespace App\Controller\Admin;

use App\Entity\Equipo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipo::class;
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

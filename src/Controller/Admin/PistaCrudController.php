<?php

namespace App\Controller\Admin;

use App\Entity\Pista;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PistaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pista::class;
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

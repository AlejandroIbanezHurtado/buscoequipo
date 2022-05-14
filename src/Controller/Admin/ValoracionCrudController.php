<?php

namespace App\Controller\Admin;

use App\Entity\Valoracion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ValoracionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Valoracion::class;
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

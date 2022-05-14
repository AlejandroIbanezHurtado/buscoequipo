<?php

namespace App\Controller\Admin;

use App\Entity\Partido;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PartidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partido::class;
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

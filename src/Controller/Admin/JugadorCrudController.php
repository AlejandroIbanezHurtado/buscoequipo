<?php

namespace App\Controller\Admin;

use App\Entity\Jugador;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class JugadorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Jugador::class;
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

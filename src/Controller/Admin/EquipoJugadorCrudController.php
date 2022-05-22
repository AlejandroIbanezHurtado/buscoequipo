<?php

namespace App\Controller\Admin;

use App\Entity\EquipoJugador;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipoJugadorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EquipoJugador::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('equipo'),
            AssociationField::new('jugador'),
        ];
    }
}

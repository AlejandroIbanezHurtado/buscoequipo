<?php

namespace App\Controller\Admin;

use App\Entity\Partido;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PartidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partido::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('equipo1'),
            AssociationField::new('equipo2'),
            AssociationField::new('pista'),
            DateTimeField::new('fecha_ini'),
            DateTimeField::new('fecha_fin'),
        ];
    }
}

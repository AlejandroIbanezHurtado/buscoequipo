<?php

namespace App\Controller\Admin;

use App\Entity\PartidoEquipo;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PartidoEquipoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PartidoEquipo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('id_equipo','Equipo'),
            AssociationField::new('id_partido','Partido'),
        ];
    }
}

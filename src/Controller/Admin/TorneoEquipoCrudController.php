<?php

namespace App\Controller\Admin;

use App\Entity\TorneoEquipo;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TorneoEquipoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TorneoEquipo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('id_torneo','Torneo'),
            AssociationField::new('id_equipo','Equipo'),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\TorneoPartido;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TorneoPartidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TorneoPartido::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('id_torneo','Torneo'),
            AssociationField::new('id_partido','Partido'),
        ];
    }
}

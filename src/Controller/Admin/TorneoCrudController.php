<?php

namespace App\Controller\Admin;

use App\Entity\Torneo;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TorneoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Torneo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            ArrayField::new('equipos'),
            AssociationField::new('equipo_creador'),
        ];
    }
}

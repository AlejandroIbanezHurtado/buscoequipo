<?php

namespace App\Controller\Admin;

use App\Entity\DetallePartido;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DetallePartidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DetallePartido::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('color')->setChoices([
                'Amarillo' => 0,
                'Rojo' => 1,
            ])
            ->renderExpanded(),
            IntegerField::new('minuto'),
            AssociationField::new('equipo'),
            AssociationField::new('jugador'),
            AssociationField::new('partido'),
        ];
    }
}

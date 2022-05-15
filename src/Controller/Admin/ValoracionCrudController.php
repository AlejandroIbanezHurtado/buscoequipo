<?php

namespace App\Controller\Admin;

use App\Entity\Valoracion;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ValoracionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Valoracion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('jugador'),
            AssociationField::new('partido'),
            IntegerField::new('puntuacion'),
            TextEditorField::new('comentario'),
        ];
    }
}

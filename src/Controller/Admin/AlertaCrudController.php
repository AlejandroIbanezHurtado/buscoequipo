<?php

namespace App\Controller\Admin;

use App\Entity\Alerta;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AlertaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Alerta::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('equipo'),
            TextEditorField::new('mensaje'),
        ];
    }
}

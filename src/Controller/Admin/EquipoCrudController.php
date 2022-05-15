<?php

namespace App\Controller\Admin;

use App\Entity\Equipo;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            AssociationField::new('capitan'),
            ImageField::new('escudo')->setUploadDir('public/bd'),
            BooleanField::new('permanente'),
        ];
    }
}

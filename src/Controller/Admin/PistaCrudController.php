<?php

namespace App\Controller\Admin;

use App\Entity\Pista;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PistaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pista::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            TextEditorField::new('descripcion'),
            ImageField::new('imagen')->setUploadDir('public/bd'),
        ];
    }
}

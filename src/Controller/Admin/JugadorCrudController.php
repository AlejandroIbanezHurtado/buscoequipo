<?php

namespace App\Controller\Admin;

use App\Entity\Jugador;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class JugadorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Jugador::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('nombre'),
            TextField::new('apellidos')->onlyOnForms(),
            TextField::new('password')->onlyOnForms(),
            ImageField::new('imagen')->setUploadDir('public/bd')->onlyOnForms(),
            BooleanField::new('isVerified','Verificado'),
            ChoiceField::new('roles')
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded()
        ];
    }
}

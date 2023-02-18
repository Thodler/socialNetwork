<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            ImageField::new('photo')
                ->setBasePath('uploads/photos')
                ->setUploadDir('public/uploads/photos')
                ->setUploadedFileNamePattern('[uuid].[extension]')
            ,
            EmailField::new('email'),
            TextField::new('pseudo'),
            TextField::new( 'plainPassword')
                ->setLabel('Mot de passe')
                ->setFormType(PasswordType::class)
                ->setRequired($pageName == Crud::PAGE_NEW)
                ->hideOnIndex(),
            ChoiceField::new('roles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER'
                ])
                ->allowMultipleChoices()
        ];
    }
}

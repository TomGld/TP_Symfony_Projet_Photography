<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        //On peut renommer les titres des différentes pages
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des utilisateurs')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un utilisateur')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un utilisateur');
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('email'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            DateField::new('age', 'Age')
            ->setFormat('dd-MM-yyyy'),
            TextField::new('city', 'Ville'),
            TextField::new('country', 'Pays'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        //On redéfinit les actions de la page index
        ->update(
            Crud::PAGE_INDEX, //Travail sur la page d'index
            Action::NEW, //Sur quelle action on veut modifier.
            fn(Action $action) => $action
            ->setIcon('fa fa-plus') //On redéfinit l'icône de l'action
            ->setLabel('Ajouter') //On redéfinit le label de l'action
            ->setCssClass('btn btn-success') //On redéfinit la classe CSS de l'action
        )
        ->update(
            Crud::PAGE_INDEX,
            Action::EDIT,
            fn(Action $action) => $action
            ->setIcon('fa fa-edit')
            ->setLabel('Modifier')
            ->setCssClass('btn btn-info')
        )
        ->update(
            Crud::PAGE_INDEX,
            Action::DELETE,
            fn(Action $action) => $action
            ->setIcon('fa fa-trash')
            ->setLabel('Supprimer')
            ->setCssClass('btn btn-danger')
        )
        ;
}
    
}

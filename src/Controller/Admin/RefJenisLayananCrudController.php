<?php

namespace App\Controller\Admin;

use App\Entity\RefJenisLayanan;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RefJenisLayananCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefJenisLayanan::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->setPermissions([
                Action::INDEX => 'ROLE_ADMIN',
                Action::NEW => 'ROLE_ADMIN',
                Action::EDIT => 'ROLE_ADMIN',
                Action::DELETE => 'ROLE_ADMIN',
            ]);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('kode'),
            TextField::new('title'),
        ];
    }
}

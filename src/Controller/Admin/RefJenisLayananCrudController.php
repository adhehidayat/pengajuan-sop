<?php

namespace App\Controller\Admin;

use App\Entity\RefJenisLayanan;
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


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('kode'),
            TextField::new('title'),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\RefLayananInternal;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class RefLayananInternalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefLayananInternal::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('icon'),
            ColorField::new('color'),
            UrlField::new('link'),
            TextareaField::new('description'),
        ];
    }
}

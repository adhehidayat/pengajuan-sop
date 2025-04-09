<?php

namespace App\Controller\Admin;

use App\Entity\RefPtsp;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RefPtspCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefPtsp::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('PTSP')
            ->setEntityLabelInSingular('PTSP')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nama')
                ->setTemplatePath('bundles/EasyAdminBundle/crud/field/linkNamaPtsp.html.twig')
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\RefPtsp;
use App\Form\RefLayananType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

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
            ->setEntityLabelInSingular('PTSP');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nama')
                ->setTemplatePath('bundles/EasyAdminBundle/crud/field/linkNamaPtsp.html.twig'),
            TextField::new('icon'),
            ColorField::new('color'),
            TextareaField::new('description'),
            CollectionField::new('refLayanan', 'Layanan')
                ->setEntryType(RefLayananType::class)
                ->hideOnForm()
        ];
    }
}

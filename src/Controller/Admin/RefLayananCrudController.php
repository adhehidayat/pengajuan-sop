<?php

namespace App\Controller\Admin;

use App\Entity\RefLayanan;
use App\Entity\RefLayananAttachment;
use App\Form\AttachmentType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

class RefLayananCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return RefLayanan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Layanan')
            ->setEntityLabelInSingular('Layanan')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            FormField::addColumn(8, propertySuffix: 'layanan'),
            FormField::addFieldset(propertySuffix: 'layanan'),
            TextField::new('nama', 'Layanan'),
            TextEditorField::new('persyaratan','Persyaratan')
                ->setTrixEditorConfig([
                    'blockAttributes' => [
                        'default' => ['tagName' => 'p'],
                        'heading1' => ['tagName' => 'h2'],
                    ],
                ]),

            FormField::addColumn(4, propertySuffix: 'ptsp'),
            FormField::addFieldset(propertySuffix: 'ptsp'),
            AssociationField::new('refPtsp', 'PTSP'),
            CollectionField::new('refLayananAttachments', 'Dokumen')
                ->setEntryType(AttachmentType::class)
                ->setFormTypeOption('allow_add', true)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms()
        ];
    }
}

<?php

namespace App\Admin\Fields;

use App\Form\AttachmentView;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class AttachmentViewField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): AttachmentViewField
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->addFormTheme('bundles/EasyAdminBundle/crud/field/pengajuan_layanan_progress_attachment.html.twig')
            ->setFormType(AttachmentView::class)
            ;
    }
}

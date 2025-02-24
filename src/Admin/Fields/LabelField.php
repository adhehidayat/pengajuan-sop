<?php

namespace App\Admin\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class LabelField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): LabelField
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('bundles/EasyAdminBundle/crud/field/label.html.twig')
            ;
    }

    public function withModal()
    {
        return $this->setCustomOption('modal', true);
    }
}

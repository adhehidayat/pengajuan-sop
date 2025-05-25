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
            ->addWebpackEncoreEntries('modalLabel')
            ->setCustomOption('color', null)
            ;
    }

    public function withModal(): LabelField
    {
        return $this->setCustomOption('modal', true);
    }

    public function withColor(array $color): LabelField
    {
        return $this->setCustomOption('color', $color);
    }
}

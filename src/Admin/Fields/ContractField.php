<?php

namespace App\Admin\Fields;

use App\Form\ContractNumberType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

final class ContractField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): ContractField
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(ContractNumberType::class)
            ->addFormTheme('bundles/EasyAdminBundle/crud/field/contract.html.twig')
            ->addWebpackEncoreEntries('contract')
            ;
    }

    public function setPtsp($value): self
    {
        $this->setCustomOption('ptsp_value', $value);

        return $this;
    }
}

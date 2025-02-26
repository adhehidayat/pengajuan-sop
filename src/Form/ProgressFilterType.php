<?php

declare(strict_types=1);

namespace App\Form;

use App\Components\Enum\PengajuanStatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgressFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => PengajuanStatusEnum::toArray()
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

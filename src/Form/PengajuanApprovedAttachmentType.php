<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\PengajuanApprovedAttachments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PengajuanApprovedAttachmentType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PengajuanApprovedAttachments::class,
        ]);
    }

    public function getParent(): string
    {
        return AttachmentType::class;
    }
}

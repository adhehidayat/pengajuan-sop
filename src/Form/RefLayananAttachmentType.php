<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\RefLayananAttachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RefLayananAttachmentType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RefLayananAttachment::class
        ]);
    }
    
    public function getParent(): string
    {
        return AttachmentType::class;
    }
}

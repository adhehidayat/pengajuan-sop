<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttachmentView extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'attachment_view';
    }
}

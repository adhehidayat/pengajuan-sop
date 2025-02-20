<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', TextType::class, [
                'label' => false,
                'data' => (new \DateTime())
                    ->format('Ymd'),
                'attr' => [
                    'readonly' => true,
//                    'disabled' => true,
                ]
            ])
            ->add('ptsp', TextType::class, [
                'label' => false
            ])
            ->add('layanan', TextType::class, [
                'label' => false
            ])
            ->add('count', TextType::class, [
                'label' => false
            ])
        ;
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//           'ptsp_value' => null
//        ]);
//    }

    public function getBlockPrefix(): string
    {
        return 'contract_number';
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\String\u;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            FormField::addColumn(8, propertySuffix: 'profile'),
            FormField::addFieldset(propertySuffix: 'profile'),
            TextField::new('nik', 'NIK'),
            TextField::new('nama', 'Nama Lengkap'),
            TextField::new('email', 'Email'),
            TelephoneField::new('telepon', 'Telepon'),
            TextareaField::new('alamat', 'Alamat'),

            FormField::addColumn(4, propertySuffix: 'login'),
            FormField::addFieldset(propertySuffix: 'login'),
            TextField::new('password', "Password")
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => "Password"],
                    'second_options' => ['label' => "Repeat Passowrd"]
                ])
                ->onlyOnForms(),
            ChoiceField::new('roles', 'Roles')->setChoices([
                "ADMIN" => 'ROLE_ADMIN',
                "OPERATOR" => 'ROLE_OPERATOR'
            ])
            ->allowMultipleChoices()
        ];
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context); // TODO: Change the autogenerated stub

        return $this->addHashPaswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context); // TODO: Change the autogenerated stub

        return $this->addHashPaswordEventListener($formBuilder);
    }

    public function addHashPaswordEventListener(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword(): \Closure
    {
        return function (FormEvent $event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            /** @var User $user */
            $user = $form->getData();
            $hash = $this->hasher->hashPassword($user, $password);
            $form->getData()->setPassword($hash);
        };
    }
}

<?php

namespace App\Controller\Admin;

use App\Admin\Fields\AttachmentViewField;
use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\Pengajuan;
use App\Entity\PengajuanProgress;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class PengajuanProgressCrudController extends AbstractCrudController
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public static function getEntityFqcn(): string
    {
        return PengajuanProgress::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $req = $this->requestStack;

        return [
            IdField::new('id')->hideOnForm(),

            FormField::addColumn(8, propertySuffix: 'pengajuan'),
            FormField::addFieldset(propertySuffix: 'pengajuan'),
            TextField::new('pengajuan.contract', 'No. Registrasi')
                ->setColumns(4)
                ->setFormTypeOption('attr', ['required' => true, 'disabled' => true]),
            TextField::new('pengajuan.instansi', 'Instansi')
                ->setColumns(8)
                ->setFormTypeOption('attr', ['required' => true, 'disabled' => true]),
            TextField::new('pengajuan.user', 'Nama')
                ->setColumns(8)
                ->setFormTypeOption('attr', ['required' => true, 'disabled' => true]),
            TextField::new('pengajuan.user.nik', 'NIK')
                ->setColumns(4)
                ->setFormTypeOption('attr', ['required' => true, 'disabled' => true]),
            CollectionField::new('attachmentPengajuan', 'Attachments'),
//            ArrayField::new('attachmentPengajuan', 'Attachment')
//                ->addFormTheme('bundles/EasyAdminBundle/crud/field/pengajuan_layanan_progress_attachment.html.twig')
//                ->setFormTypeOption('mapped', false)
//                ->formatValue(function ($value) {
//                    dump($value);
//                }),
            AttachmentViewField::new('attachmentPengajuan', 'Attachments'),

            FormField::addColumn(4, propertySuffix: 'progress'),
            FormField::addFieldset(propertySuffix: 'progress'),
            ChoiceField::new('status', 'Status')
                ->setChoices(PengajuanStatusEnum::toArray())
                ->renderExpanded(),
            TextareaField::new('ket', 'Keterangan')
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $req = $this->requestStack->getCurrentRequest();
        $id = $req->get('pengajuan');

        /** @var ManagerRegistry $doctrine */
        $doctrine = $this->container->get('doctrine');
        $repository = $doctrine->getManagerForClass(Pengajuan::class)->getRepository(Pengajuan::class)->find($id);


        /** @var User $user */
        $user = $this->getUser();

        /** @var PengajuanProgress $parent */
        $parent = parent::createEntity($entityFqcn);
        $parent->setUser($user);
        $parent->setPengajuan($repository);
        $parent->setAttachmentPengajuan($repository->getAttachment()->toArray());

        return $parent;
    }

}

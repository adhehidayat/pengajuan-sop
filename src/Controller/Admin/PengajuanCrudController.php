<?php

namespace App\Controller\Admin;

use App\Admin\Fields\LabelField;
use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\Pengajuan;
use App\Entity\PengajuanProgress;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class PengajuanCrudController extends AbstractCrudController
{
    public function __construct(private AdminUrlGenerator $urlGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Pengajuan::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Crud::PAGE_NEW)
            ->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN')
            ->add(Action::INDEX,  Action::new(Crud::PAGE_DETAIL, 'Verifikasi')
                ->displayIf(function (Pengajuan $pengajuan) {
                    $progress = $pengajuan->getProgress();
                    if ($progress->last() !== false) {
                        return $progress->last()->getStatus() !== PengajuanStatusEnum::DITERIMA;
                    }

                    return true;
                })
                ->linkToUrl(function (Pengajuan $pengajuan) {
                    return $this->urlGenerator
                        ->setController(PengajuanProgress::class)
                        ->setAction(Crud::PAGE_NEW)
                        ->set('pengajuan', $pengajuan->getId())
                        ->generateUrl()
                        ;
                })
            )
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('contract', 'No. Registrasi'),
            TextField::new('instansi', 'Instansi'),
            TextField::new('user.nama', 'Nama'),
            TextField::new('user.nik', 'NIK'),
            TextField::new('user.email', 'Email'),
            LabelField::new('lastProgress', 'Progress')
                ->withModal()
        ];
    }
}

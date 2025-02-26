<?php

namespace App\Controller\Admin;

use App\Admin\Fields\AttachmentViewField;
use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\Pengajuan;
use App\Entity\PengajuanProgress;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class PengajuanProgressCrudController extends AbstractCrudController
{
    public function __construct(private readonly RequestStack $requestStack, private readonly AdminUrlGenerator $adminUrlGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return PengajuanProgress::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            FormField::addColumn(8, propertySuffix: 'pengajuan'),
            FormField::addFieldset(propertySuffix: 'pengajuan'),
            TextField::new('pengajuan.contract', 'No. Registrasi')
                ->setColumns(4)
                ->setDisabled()
                ->setVirtual(true),
            TextField::new('pengajuan.instansi', 'Instansi')
                ->setColumns(8)
                ->setDisabled()
                ->setVirtual(true),
            TextField::new('pengajuan.user', 'Nama')
                ->setColumns(8)
                ->setDisabled()
                ->setVirtual(true),
            TextField::new('pengajuan.user.nik', 'NIK')
                ->setColumns(4)
                ->setDisabled()
                ->setVirtual(true),
            AttachmentViewField::new('attachmentPengajuan', 'Attachments'),

            FormField::addColumn(4, propertySuffix: 'progress'),
            FormField::addFieldset(propertySuffix: 'progress'),
            ChoiceField::new('status', 'Status')
                ->setChoices(PengajuanStatusEnum::toArray())
                ->renderExpanded(),
            TextareaField::new('ket', 'Keterangan')
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createEntity(string $entityFqcn): PengajuanProgress
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

    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {
        $url = $this->adminUrlGenerator
            ->setController(PengajuanCrudController::class)
            ->setAction(Crud::PAGE_INDEX)
            ->unset('pengajuan')
            ->generateUrl();

        return $this->redirect($url);
    }
}

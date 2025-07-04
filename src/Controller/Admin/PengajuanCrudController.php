<?php

namespace App\Controller\Admin;

use App\Admin\Fields\AttachmentViewField;
use App\Admin\Fields\LabelField;
use App\Admin\Filters\ProgressFilter;
use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\Narasumber;
use App\Entity\Pengajuan;
use App\Entity\PengajuanApprovedAttachments;
use App\Entity\PengajuanProgress;
use App\Entity\PengajuanProgressHistory;
use App\Entity\User;
use App\Form\AttachmentType;
use App\Form\PengajuanApprovedAttachmentType;
use App\Form\PengajuanAttachmentType;
use App\Message\PengajuanApprovedMessage;
use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\FieldProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PengajuanCrudController extends AbstractCrudController
{
    public function __construct(private readonly AdminUrlGenerator   $urlGenerator,
                                private readonly MessageBusInterface $messageBus,
                                private readonly NormalizerInterface $normalizer,
                                private readonly SurveyRepository    $surveyRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Pengajuan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_EDIT, function (Pengajuan $entity) {
                if ($entity->getUser()->getId() === $this->getUser()->getId()) {
                    return 'Edit';
                }

                return 'Verifikasi';
            })
            ->showEntityActionsInlined()
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        /** @var User $user */
        $user = $this->getUser();
        $parent = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters); // TODO: Change the autogenerated stub

//        if (in_array('ROLE_USER', $user->getRoles())) {
//            return $parent->andWhere('entity.user = :user')
//                ->setParameter('user', $user);
//        } else if (in_array('ROLE_OPERATOR', $user->getRoles())) {
//            $status = PengajuanStatusEnum::toArray();
//            $status->remove('Dalam Proses');
//
//            return $parent
//                ->join('entity.pengajuanProgress', 'pengajuan_progress')
//                ->andWhere($parent->expr()->orX(
//                    'pengajuan_progress.user = :user',
//                   $parent->expr()->isNull('pengajuan_progress.user')
//                ))
//                ->setParameter('user', $user)
//                ->addOrderBy('pengajuan_progress.createAt', 'DESC')
////                ->setParameter('progress', $status->toArray());
//            ;
//        } else
        if (in_array('ROLE_OPERATOR_BIDANG', $user->getRoles())) {
            $status = PengajuanStatusEnum::toArray();
            $status->remove('Dalam Proses');

            return $parent
                ->join('entity.ptsp', 'ptsp')
                ->andWhere('ptsp.id = :ptsp')
                ->setParameter('ptsp', $user->getPtsp()->getId());
        }

        return $parent;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ProgressFilter::new('pengajuanProgress', 'Status')
                ->renderExpanded()
                ->choiceList(PengajuanStatusEnum::forChoices($this->getUser()->getRoles(), 'filter'))
            );
    }

    public function configureActions(Actions $actions): Actions
    {
        $firstOpenVerifikasi = Action::new('setVerifikasi', 'Verifikasi', 'fa fa-check')
            ->linkToCrudAction('firstVerifikasi')
            ->displayIf(function (Pengajuan $pengajuan) {
                if (in_array('ROLE_OPERATOR_BIDANG', $this->getUser()->getRoles())) {
                    return $pengajuan->getPengajuanProgress()->getStatus() === PengajuanStatusEnum::MENUNGGU;
                }

                if (in_array('ROLE_OPERATOR', $this->getUser()->getRoles())) {
                    return $pengajuan->getPengajuanProgress()->getStatus() === PengajuanStatusEnum::PERLU_PERBAIKAN;
                }

                return false;
            })
            ->setCssClass('btn btn-secondary');

        return $actions
            ->disable(Crud::PAGE_NEW)
//            ->setPermission(Action::EDIT, 'ROLE_OPERATOR')
//            ->setPermission(Action::EDIT, 'ROLE_OPERATOR_BIDANG')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN')
            ->add(Action::INDEX, $firstOpenVerifikasi)
            ->update(Action::INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setCssClass('btn btn-primary')
                    ->setLabel(function (Pengajuan $entity) {
                        if ($entity->getUser()->getId() === $this->getUser()->getId()) {
                            return 'Edit';
                        }

                        return 'Verifikasi';
                    })
                    ->displayIf(function (Pengajuan $pengajuan) {
                        /** @var User $user */
                        $user = $this->getUser();
                        return PengajuanStatusEnum::isEditable($user->getRoles(), $pengajuan->getPengajuanProgress()->getStatus());
                    })
                    ->setIcon('fa fa-check');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($pageName === Crud::PAGE_EDIT) {
            $defaultForm = [
                FormField::addColumn(8, propertySuffix: 'pengajuan'),
                FormField::addFieldset(propertySuffix: 'pengajuan'),
                TextField::new('contract', 'No. Registrasi')
                    ->setColumns(12)
                    ->setDisabled()
                    ->setVirtual(true),
                TextField::new('user.nik', 'NIK')
                    ->setColumns(4)
                    ->setDisabled()
                    ->setVirtual(true),
                TextField::new('user.nama', 'Nama')
                    ->setColumns(8)
                    ->setDisabled()
                    ->setVirtual(true),
                AttachmentViewField::new('attachment', 'Attachments'),
            ];

            if (in_array("ROLE_OPERATOR_BIDANG", $user->getRoles())) {
                return array_merge($defaultForm, [
                    FormField::addColumn(4, propertySuffix: 'progress'),
                    FormField::addFieldset(propertySuffix: 'progress'),
                    ChoiceField::new('pengajuanProgress.status', 'Status')
                        ->setChoices(
                            function (?Pengajuan $pengajuan) {
                                return PengajuanStatusEnum::forFields($this->getUser()->getRoles(), $pengajuan->getPengajuanProgress()->getStatus());
                            }
                        )
                        ->setFormTypeOption('placeholder', false)
                        ->setRequired(true)
                        ->addWebpackEncoreEntries('pengajuan_approved_uploaded')
                        ->renderExpanded(),

                    CollectionField::new('approvedAttachments', 'Approved File')
                        ->setEntryType(PengajuanApprovedAttachmentType::class)
                        ->setCssClass('approved_uploaded'),
                    TextareaField::new('pengajuanProgress.ket', 'Keterangan')
                ]);
            }

            return array_merge($defaultForm, [
                FormField::addColumn(4, propertySuffix: 'progress'),
                FormField::addFieldset(propertySuffix: 'progress'),
                ChoiceField::new('pengajuanProgress.status', 'Status')
                    ->setChoices(
                        function (?Pengajuan $pengajuan) {
                            return PengajuanStatusEnum::forFields($this->getUser()->getRoles(), $pengajuan->getPengajuanProgress()->getStatus());
                        }
                    )
                    ->setFormTypeOption('placeholder', false)
                    ->setRequired(true)
                    ->addWebpackEncoreEntries('pengajuan_approved_uploaded')
                    ->renderExpanded(),

                AttachmentViewField::new('approvedAttachments', 'Attachments'),

                TextareaField::new('pengajuanProgress.ket', 'Keterangan')
            ]);
        }


        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('contract', 'No. Registrasi'),
//            TextField::new('instansi', 'Instansi'),
            AssociationField::new('ptsp', 'PTSP')
                ->setTemplatePath('bundles/EasyAdminBundle/crud/field/generic.html.twig'),
//            AssociationField::new('layanan', 'Layanan'),
            TextField::new('user.nik', 'NIK'),
            TextField::new('user.nama', 'Nama'),
//            TextField::new('user.email', 'Email'),
            TextField::new('user.telepon', 'Telepon'),
            LabelField::new('pengajuanProgress.status', 'Progress')
                ->withModal()
                ->withColor(PengajuanStatusEnum::getBadgeColors())
        ];
    }

//    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
//    {
//        /** @var Pengajuan $entity */
//        $entity = $entityInstance;
//
//        if ($entity->getPengajuanProgress()->getStatus()->name === "DITERIMA") {
//            try {
//                /** @var Narasumber $user */
//                $user = $entity->getUser();
//                $respon = $this->normalizer->normalize($user, 'array');
//
//                $surveyRepository = $this->surveyRepository->findOneBy(['status' => true]);
//
//
//                $this->messageBus->dispatch(new PengajuanApprovedMessage(
//                    $entity->getContract(),
//                    $respon,
//                    $surveyRepository->getSurvey()
//                ));
//            } catch (\Throwable $e) {
////                $this->addFlash('info', $e);
//            }
//        }
//
//        parent::persistEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
//    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function firstVerifikasi(AdminContext $context): RedirectResponse
    {
        $className = self::getEntityFqcn();
        $request = $context->getRequest();

        $entityId = $request->get('entityId');

        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        $repository = $entityManager->find($className, $entityId);

        /** @var PengajuanProgress $changeProgress */
        $changeProgress = $repository->getPengajuanProgress();

        $oldStatus = $changeProgress->getStatus();
        $newStatus = null;

        if ($oldStatus === PengajuanStatusEnum::MENUNGGU) {
            $newStatus = PengajuanStatusEnum::DALAM_PROSES;
        }

        if ($oldStatus === PengajuanStatusEnum::PERLU_PERBAIKAN) {
            $newStatus = PengajuanStatusEnum::PROSES_PERBAIKAN;
        }

        $changeProgress->setStatus($newStatus);
        $changeProgress->setUser($this->getUser());

        $repository->setPengajuanProgress($changeProgress);

        $newHistoryProgress = new PengajuanProgressHistory();
        $newHistoryProgress->setUser($this->getUser());
        $newHistoryProgress->setPengajuan($repository);
        $newHistoryProgress->setStatus($oldStatus);

        $this->container->get('doctrine')
            ->getManagerForClass(PengajuanProgressHistory::class)
            ->persist($newHistoryProgress);

        $this->persistEntity($entityManager, $repository);
        $redirect = $this->urlGenerator
            ->setAction(Crud::PAGE_EDIT)
            ->setEntityId($entityId)
            ->generateUrl();

        return $this->redirect($redirect);
    }

}

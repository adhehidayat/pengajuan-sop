<?php

namespace App\Controller\Admin;

use App\Admin\Fields\ContractField;
use App\Entity\Pengajuan;
use App\Form\AttachmentType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class PengajuanNewCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::SAVE_AND_RETURN);
    }

    public static function getEntityFqcn(): string
    {
        return Pengajuan::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $request = $this->requestStack->getCurrentRequest();
        $filterValue = $request?->query->all('filters'); // Ambil parameter dari URL

        $ptspFilter = $filterValue['ptsp']['value'] ?? null;
        $ptsp_id = $filterValue['ptsp_id']['value'] ?? null;


        $layananField = AssociationField::new('layanan', 'Pilih Layanan')
            ->setQueryBuilder(function (QueryBuilder $qb) use ($ptspFilter) {
                if ($ptspFilter) {
                    return $qb
                        ->join('entity.refPtsp', 'ptsp')
                        ->where('ptsp.nama =:nama')
                        ->setParameter('nama', $ptspFilter);
                }
                return $qb;
            });


        return [
            IdField::new('id')->hideOnForm(),

            FormField::addColumn(8, propertySuffix: 'pengaduan'),
            FormField::addFieldset(propertySuffix: 'pengaduan'),
            ContractField::new('contract', 'Nomor')
                ->setPtsp($ptsp_id),
            TextField::new('nama', 'Nama Lengkap'),
            TextField::new('email', 'Email'),
            TelephoneField::new('telepon', 'Telepon'),
            TextareaField::new('alamat', 'Alamat'),
            CollectionField::new('attachment')
                ->setEntryType(AttachmentType::class),

            FormField::addColumn(4, propertySuffix: 'layanan'),
            FormField::addFieldset(propertySuffix: 'layanan'),
            $layananField,
            TextareaField::new('persyaratan')
                ->setFormTypeOptions([
                    'attr' => [
                        'readonly' => true
                    ],
                    'mapped' => false
                ])
                ->addFormTheme('bundles/EasyAdminBundle/crud/field/persyaratan.html.twig'),
            CollectionField::new('refLayananAttachments', 'Link Standar Pelayanan, SOP, dan Dokumen Terkait')
                ->addFormTheme('bundles/EasyAdminBundle/crud/field/pengajuan_layanan_attachment.html.twig')
                ->setFormTypeOption('mapped', false)
        ];
    }

}

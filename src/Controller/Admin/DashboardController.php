<?php

namespace App\Controller\Admin;

use App\Entity\Pengajuan;
use App\Entity\RefJenisLayanan;
use App\Entity\RefLayanan;
use App\Entity\RefLayananInternal;
use App\Entity\RefPtsp;
use App\Entity\User;
use App\Service\Chart\ChartPengajuanService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $entity, private ChartPengajuanService $chartPengajuan)
    {
    }

    public function index(): Response
    {
        $groupPtsp = $this->entity->getRepository(RefLayanan::class)->findGroupPtsp();

        $chartJumlahPengajuan = $this->chartPengajuan->chartPengaduanByStatus();



        return $this->render('bundles/EasyAdminBundle/page/Dashboard.html.twig', [
            'group_ptsp' => $groupPtsp,
            'jumlahPengajuan' => $chartJumlahPengajuan
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin')
            ->generateRelativeUrls()
            ->disableUrlSignatures()
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Master')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('PTSP', 'fa fa-tags', RefPtsp::class)
            ->setPermission('ROLE_ADMIN');
//        yield MenuItem::linkToCrud('Layanan', 'fa fa-cog', RefLayanan::class)
//            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Layanan Internal', '', RefLayananInternal::class);
        yield MenuItem::linkToCrud('Layanan', '', RefJenisLayanan::class);

        yield MenuItem::section('Administrator')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class)
            ->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Pengajuan');
        yield MenuItem::linkToCrud('Pengajuan', 'fas fa-paper-plane',Pengajuan::class)
            ->setAction(Crud::PAGE_INDEX)
            ->setController(PengajuanCrudController::class)
            ->setPermission('ROLE_USER');
    }
}

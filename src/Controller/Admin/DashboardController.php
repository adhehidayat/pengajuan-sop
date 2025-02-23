<?php

namespace App\Controller\Admin;

use App\Entity\Pengajuan;
use App\Entity\RefLayanan;
use App\Entity\RefPtsp;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $entity)
    {
    }

    public function index(): Response
    {
        $groupPtsp = $this->entity->getRepository(RefLayanan::class)->findGroupPtsp();

        return $this->render('bundles/EasyAdminBundle/page/Dashboard.html.twig', [
            'group_ptsp' => $groupPtsp
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kemeneg')
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Master');
        yield MenuItem::linkToCrud('PTSP', 'fa fa-tags', RefPtsp::class);
        yield MenuItem::linkToCrud('Layanan', 'fa fa-cog', RefLayanan::class);

        yield MenuItem::section('Administrator');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);

        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Pengajuan', 'fas fa-paper-plane',Pengajuan::class)
            ->setAction(Crud::PAGE_INDEX)
            ->setController(PengajuanCrudController::class);
    }
}

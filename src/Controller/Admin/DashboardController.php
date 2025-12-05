<?php

namespace App\Controller\Admin;

use App\Entity\Accounts;
use App\Entity\AccountServiceAssignment;
use App\Entity\AccountTransactions;
use App\Entity\Client;
use App\Entity\CostOfLife;
use App\Entity\Currency;
use App\Entity\Days;
use App\Entity\Holiday;
use App\Entity\Notes;
use App\Entity\ProductService;
use App\Entity\Rate;
use App\Entity\TaskLists;
use App\Entity\Tasks;
use App\Entity\TimeSystem;
use App\User\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ChartBuilderInterface $chartBuilder,
    ) {}

    public function index(): Response
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        // ...set chart data and options somehow

        return $this->render('admin/my-dashboard.html.twig', [
            'chart' => $chart,
        ]);
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wuwei')
            ->setDefaultColorScheme('dark');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Business');
        yield MenuItem::linkToCrud('Clients', 'fa fa-tags', Client::class);
        yield MenuItem::linkToCrud('Accounts', 'fa fa-tags', Accounts::class);
        yield MenuItem::linkToCrud('Account Transactions', 'fa fa-tags', AccountTransactions::class);
        yield MenuItem::section('Operation');
        yield MenuItem::linkToCrud('Days', 'fa fa-tags', Days::class);
        yield MenuItem::linkToCrud('TaskLists', 'fa fa-tags', TaskLists::class);
        yield MenuItem::linkToCrud('Tasks', 'fa fa-tags', Tasks::class);
        yield MenuItem::section('Wuwei - Settings');
        yield MenuItem::linkToCrud('Users', 'fa fa-tags', User::class);
        yield MenuItem::linkToCrud('Currency', 'fa fa-tags', Currency::class);
        yield MenuItem::linkToCrud('Rate', 'fa fa-tags', Rate::class);
        yield MenuItem::linkToCrud('Cost Of Life', 'fa fa-tags', CostOfLife::class);
        yield MenuItem::linkToCrud('Holiday', 'fa fa-tags', Holiday::class);
        yield MenuItem::linkToCrud('Time System', 'fa fa-tags', TimeSystem::class);
        yield MenuItem::section('Wuwei - New');
        yield MenuItem::linkToCrud('Product Service', 'fa fa-tags', ProductService::class);
        yield MenuItem::linkToCrud('Account Service Assignment', 'fa fa-tags', AccountServiceAssignment::class);
        yield MenuItem::section('Other');
        yield MenuItem::linkToCrud('Notes', 'fa fa-tags', Notes::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}

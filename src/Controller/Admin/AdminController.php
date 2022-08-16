<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
        
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
        
        
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 1. You can make your dashboard redirect to some common page of your backend
        
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        // return $this->redirect('app_home')
        return Dashboard::new()
            ->setTitle('Lejardindescurieux');
    }

    public function configureMenuItems(): iterable
    {
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 7a18dc3c266707305957159872f6273605c424ed
        yield MenuItem::section('Mon Commerce', 'fa fa-home');

        // Menu pour le produit
        yield MenuItem::section('Produit', 'fa-brands fa-product-hunt');
        yield MenuItem::subMenu('Action', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('Ajouter un produit', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Afficher', 'fas fa-eye', Article::class)
        ]);
        
        // Category menu
        yield MenuItem::section('Category', 'fa-brands fa-product-hunt');
        yield MenuItem::subMenu('Action', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('Ajouter un category', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Afficher', 'fas fa-eye', Category::class)
        ]);

        // User
        yield MenuItem::section('Utilisateur', 'fa-brands fa-user');
        yield MenuItem::subMenu('Action', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('Afficher', 'fas fa-eye', User::class)
        ]);

<<<<<<< HEAD
=======
=======

        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
>>>>>>> 6da987df0a85de2aa3bec20a251f887df6ca2eab
>>>>>>> 7a18dc3c266707305957159872f6273605c424ed
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

        return [
            MenuItem::linkToDashboard('Home', 'fa fa-home')
        ];


        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);


    }
    
    
}

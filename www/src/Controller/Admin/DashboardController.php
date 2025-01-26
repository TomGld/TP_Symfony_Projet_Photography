<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        

        //Redirection par defaut vers la liste des genres
        $url = $this->adminUrlGenerator
        ->setController(UserCrudController::class)
        ->setAction('index')
        ->generateUrl();
        return $this->redirect($url);
        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard administateur');
    }

    public function configureMenuItems(): iterable
    {
        //menu principal
        //Accueil du site
        yield MenuItem::linkToUrl('Accueil du site', 'fa fa-home', '/');
        yield MenuItem::linkToDashboard('Accueil - dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

        //Menu "Projects"
        yield MenuItem::subMenu('Projets', 'fa fa-tags')->setSubItems([
            MenuItem::linkToCrud('Ajouter un projet', 'fa fa-plus-circle', Project::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les projets', 'fa fa-eye', Project::class) // Si l'on ne lui dit pas de renvoyer quelque part, il renvoie automatiquement sur la page index.
            ]);

        
    }
}

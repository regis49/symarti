<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\Media;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
        
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
        return $this->redirect($url);
    }


   

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            
            ->setTitle('SymArtiFormation');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'app_home');
        
        if($this->isGranted('ROLE_AUTHOR')){
        yield MenuItem::subMenu('Formations', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Toutes les formations', 'fas fa-newspaper', Article::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class)
        ]);

        yield MenuItem::subMenu('Médias', 'fas fa-photo-video')->setSubItems([
            MenuItem::linkToCrud('Médiathèque', 'fas fa-photo-video', Media::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Media::class)->setAction(Crud::PAGE_NEW)
        ]);
    }

        if($this->isGranted('ROLE_ADMIN')){
        yield MenuItem::subMenu('Menus', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Pages', 'fas fa-file', Menu::class)->setController(MenuCrudController::class)->setAction(Action::INDEX)->setQueryParameter('submenuIndex', MenuCrudController::MENU_PAGES),
            MenuItem::linkToCrud('Formations', 'fas fa-newspaper', Menu::class)->setController(MenuCrudController::class)->setAction(Action::INDEX)->setQueryParameter('submenuIndex', MenuCrudController::MENU_ARTICLES),
            MenuItem::linkToCrud('Liens personnalisés', 'fas fa-link', Menu::class)->setController(MenuCrudController::class)->setAction(Action::INDEX)->setQueryParameter('submenuIndex', MenuCrudController::MENU_LINKS),
            MenuItem::linkToCrud('Catégories', 'fab fa-delicious', Menu::class)->setAction(Action::INDEX)->setQueryParameter('submenuIndex', MenuCrudController::MENU_CATEGORIES)
           
        ]);
    }

    if($this->isGranted('ROLE_ADMIN')){
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);
    
        yield MenuItem::subMenu('Comptes', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Tous les comptes', 'fas fa-user-friends', User::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW)
        ]);
    }
    
    }
}

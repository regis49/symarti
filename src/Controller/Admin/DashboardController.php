<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
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
       /*$url =$this->adminUrlGenerator
        ->unsetAll()
        ->setController(ArticleCrudController::class)
        ->setAction(Action::INDEX)
        ->set(EA::MENU_INDEX, count($menu))
        ->set(EA::SUBMENU_INDEX, count($subItems))
        ->generateUrl();

       /* $url =  $this->adminUrlGenerator
        ->setDashboard(DashboardController::class)
        ->setController(ArticleCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();*/

        return $this->redirect($url);
    }


    private function getIndexLinkForCrudController(string $controller): string
{
    return $this->adminUrlGenerator
        ->unsetAll()
        ->setController($controller)
        ->setAction(Action::INDEX)
        ->set('menuIndex', $this->determineMenuIndexForEntity($controller::getEntityFqcn()))
        ->generateUrl();
}

private function determineMenuIndexForEntity(string $entity): ?int
{
    $menuItems = $this->configureMenuItems();

    foreach ($menuItems as $id => $menuItem) {
        /* @var MenuItemInterface $menuItem */
        $routeParameter = $menuItem->getAsDto()->getRouteParameters();
        if (
            is_array($routeParameter) &&
            array_key_exists(EA::ENTITY_FQCN, $routeParameter) &&
            $routeParameter[EA::ENTITY_FQCN] == $entity
        ) {
            return $id;
        }
    }

    return null;
}

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            
            ->setTitle('SymArtiFormation');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'app_home');
        
        yield MenuItem::subMenu('Formations', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Toutes les formations', 'fas fa-newspaper', Article::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class)
        ]);

        yield MenuItem::subMenu('Menus', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Pages', 'fas fa-file', Menu::class),
            MenuItem::linkToCrud('Formations', 'fas fa-newspaper', Menu::class),
            MenuItem::linkToCrud('Liens personnalisés', 'fas fa-link', Menu::class),
            MenuItem::linkToCrud('Catégories', 'fab fa-delicious', Menu::class)
           
        ]);

        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);
    }
}

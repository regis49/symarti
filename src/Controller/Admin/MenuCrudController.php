<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuCrudController extends AbstractCrudController
{

    const MENU_PAGES = 0;
    const MENU_ARTICLES = 1;
    const MENU_LINKS = 2;
    const MENU_CATEGORIES = 3;

    public function __construct(
        private RequestStack $requestStack
    ){    
    }


    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }   

    public function configureCrud(Crud $crud): Crud
    {
        $subMenuIndex = $this->getSubMenuIndex();
        $entitylabelInSingular = 'un menu';

        $entitylabelInPlural = match ($subMenuIndex){
            self::MENU_ARTICLES => 'Articles',
            self::MENU_CATEGORIES => 'Catégories',
            self::MENU_LINKS => 'Liens personnalisés',
            default => 'Pages'
        };

        return $crud
            ->setEntityLabelInSingular($entitylabelInSingular)
            ->setEntityLabelInPlural($entitylabelInPlural);
    }

    private function getSubMenuIndex(): int
    {
        return $this->requestStack->getMainRequest()->query->getInt('submenuIndex');
    }


    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

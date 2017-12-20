<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Categories;
use AppBundle\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    const ITEM_CLASS = 'list-group-item';

    private $promotionsSubMenu  = [
        'List Active Promotions'                => ['route' => 'listPromotions'],
        'Add Products To Promotion'             => ['route' => 'addPromotion'],
        'Add Categories To Promotion'           => ['route' => 'addCategoryToPromotion'],
    ];

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        /* @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $menu = $factory->createItem('root');

        $menu->addChild('My Shopping Cart', ['route' => 'showOrderedProductsByUser'])
             ->setAttribute('class', self::ITEM_CLASS)
             ->setExtra('translation_domain', false);

        if ($user->hasRole('ROLE_ADMIN')) {
            $menu->addChild('Add Category', ['route' => 'addCategory'])
                ->setAttribute('class', self::ITEM_CLASS)
                ->setExtra('translation_domain', false);
        }

        $menu->addChild('Categories',
                        [
                            'dropdown' => true,
                            'caret'    => true
                        ])
             ->setAttribute('class', self::ITEM_CLASS)
             ->setExtra('translation_domain', false);

        $subCategories = $this->container
                              ->get('app.services.categories')
                              ->getAllCategories();

        /* @var Categories $category */
        foreach ($subCategories as $category) {
            $menu->getChild('Categories')
                 ->addChild($category->getName(),
                            [
                                'route' => 'applyCategories',
                                'routeParameters' => [
                                    'name' => $category->getName()
                                ]
                            ])
                ->setAttribute('class', 'a')
                ->setExtra('translation_domain', false);
        }

        $menu->addChild('All Products', ['route' => 'allProducts'])
             ->setAttribute('class', self::ITEM_CLASS)
             ->setExtra('translation_domain', false);

        $menu->addChild('Add Product', ['route' => 'addProduct'])
             ->setAttribute('class', self::ITEM_CLASS)
             ->setExtra('translation_domain', false);

        $menu->addChild('Show Bought Products', ['route' => 'showBoughtProducts'])
            ->setAttribute('class', self::ITEM_CLASS)
            ->setExtra('translation_domain', false);

        if ($user->hasRole('ROLE_ADMIN')) {

            $menu->addChild('Promotions')
                ->setAttribute('class', self::ITEM_CLASS)
                ->setExtra('translation_domain', false);

            foreach ($this->promotionsSubMenu as $cellName => $route) {
                $menu->getChild('Promotions')
                     ->addChild($cellName, $route)
                     ->setAttribute('class', self::ITEM_CLASS)
                     ->setExtra('translation_domain', false);
            }

            $menu->addChild('User Management', ['route' => 'getAllUsers'])
                 ->setAttribute('class', self::ITEM_CLASS)
                 ->setExtra('translation_domain', false);
        }

        return $menu;
    }
}
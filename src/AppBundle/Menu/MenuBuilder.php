<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Categories;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    const ITEM_CLASS = 'list-group-item';

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('My Shopping Cart', ['route' => 'homepage'])
             ->setAttribute('class', self::ITEM_CLASS)
             ->setExtra('translation_domain', false);

        $menu->addChild('Categories',
                        [
                            'route'    => 'showCategories',
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

        return $menu;
    }
}
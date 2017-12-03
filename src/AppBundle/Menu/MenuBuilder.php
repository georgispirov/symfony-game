<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Categories;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $em   = $this->container->get('doctrine.orm.entity_manager');
        $menu = $factory->createItem('root');

        $menu->addChild('My Shopping Cart', ['route' => 'homepage'])
             ->setAttribute('class', 'list-group-item');

        $menu->addChild('Categories', [
                                                'route'    => 'showCategories',
                                                'dropdown' => true,
                                                'caret'    => true
            ])->setAttribute('class', 'list-group-item');

        $subCategories = $em->getRepository(Categories::class)
            ->findAll();

        foreach ($subCategories as $category) {
            $menu->getChild('Categories')
                 ->addChild($category->getName(), [
                                                       'route' => 'applyCategories',
                                                       'routeParameters' => [
                                                           'name' => $category->getName()
                                                       ]
                 ])->setAttribute('class', 'a');
        }

        $menu->addChild('All Products', ['route' => 'homepage'])
             ->setAttribute('class', 'list-group-item');

        $menu->addChild('Add Product', ['route' => 'addProduct'])
             ->setAttribute('class', 'list-group-item');


        return $menu;
    }
}
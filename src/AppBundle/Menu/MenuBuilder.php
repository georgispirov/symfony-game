<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('My Shopping Cart', ['route' => 'homepage'])
             ->setAttribute('class', 'list-group-item');

        $menu->addChild('Categories', ['route' => 'homepage'])
             ->setAttribute('class', 'list-group-item');

        $menu->addChild('All Products', ['route' => 'homepage'])
             ->setAttribute('class', 'list-group-item');

        return $menu;
    }
}
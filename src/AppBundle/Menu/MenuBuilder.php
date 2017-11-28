<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('User Management')
             ->setAttributes([
                 'class' => 'nav-item'
             ]);

        $menu['User Management']->addChild('My Cart', [
            'route' => 'cart-preview'
        ]);

        $menu->addChild('Categories', [
            'route' => 'homepage'
        ]);

        $menu->addChild('All Products', [
            'route' => 'homepage'
        ]);
            $rr = new MenuFactory();
        return $menu;
    }
}
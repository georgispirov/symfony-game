<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class HeaderMenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home');

        $menu->addChild('Profile', ['route' => 'fos_user_profile_edit']);

        $menu->addChild('Logged in as: ' . $this->container
                             ->get('security.token_storage')
                             ->getToken()
                             ->getUsername());

        $menu->addChild('Logout', ['route' => 'fos_user_profile_edit']);
        return $menu;
    }
}
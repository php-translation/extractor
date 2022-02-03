<?php

namespace Translation\Extractor\Tests\Resources\Php\Knp;

use Knp\Menu\MenuFactory;
use Knp\Menu\ItemInterface;

class Menu
{
    public function buildMenu(): ItemInterface
    {
        $factory = new MenuFactory();
        $menu = $factory->createItem('A menu');
        $menu->addChild('my.first.label', ['uri' => '/']);

        $menu->addChild('foo', ['uri' => '/']);
        $menu['foo']->setLabel('foo.first.label');
        $menu->getChild('foo')->setLabel('foo.second.label');

        $menu['foo']->setLinkAttribute('title', 'my.first.title');
        $menu->getChild('foo')->setLinkAttribute('title', 'my.second.title');

        return $menu;
    }
}

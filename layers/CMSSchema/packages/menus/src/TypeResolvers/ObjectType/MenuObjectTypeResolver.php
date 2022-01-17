<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MenuTypeDataLoader $menuTypeDataLoader = null;
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    final public function setMenuTypeDataLoader(MenuTypeDataLoader $menuTypeDataLoader): void
    {
        $this->menuTypeDataLoader = $menuTypeDataLoader;
    }
    final protected function getMenuTypeDataLoader(): MenuTypeDataLoader
    {
        return $this->menuTypeDataLoader ??= $this->instanceManager->getInstance(MenuTypeDataLoader::class);
    }
    final public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        return $this->menuTypeAPI ??= $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }

    public function getTypeName(): string
    {
        return 'Menu';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a navigation menu', 'menus');
    }

    public function getID(object $object): string | int | null
    {
        $menu = $object;
        return $this->getMenuTypeAPI()->getMenuID($menu);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMenuTypeDataLoader();
    }
}

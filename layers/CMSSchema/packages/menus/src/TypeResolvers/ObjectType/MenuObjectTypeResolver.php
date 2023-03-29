<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuObjectTypeDataLoader;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MenuObjectTypeDataLoader $menuObjectTypeDataLoader = null;
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    final public function setMenuObjectTypeDataLoader(MenuObjectTypeDataLoader $menuObjectTypeDataLoader): void
    {
        $this->menuObjectTypeDataLoader = $menuObjectTypeDataLoader;
    }
    final protected function getMenuObjectTypeDataLoader(): MenuObjectTypeDataLoader
    {
        /** @var MenuObjectTypeDataLoader */
        return $this->menuObjectTypeDataLoader ??= $this->instanceManager->getInstance(MenuObjectTypeDataLoader::class);
    }
    final public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        /** @var MenuTypeAPIInterface */
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

    public function getID(object $object): string|int|null
    {
        $menu = $object;
        return $this->getMenuTypeAPI()->getMenuID($menu);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMenuObjectTypeDataLoader();
    }
}

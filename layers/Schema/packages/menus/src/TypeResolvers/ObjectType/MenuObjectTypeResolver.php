<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class MenuObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected MenuTypeDataLoader $menuTypeDataLoader;
    protected MenuTypeAPIInterface $menuTypeAPI;

    #[Required]
    public function autowireMenuObjectTypeResolver(
        MenuTypeDataLoader $menuTypeDataLoader,
        MenuTypeAPIInterface $menuTypeAPI,
    ): void {
        $this->menuTypeDataLoader = $menuTypeDataLoader;
        $this->menuTypeAPI = $menuTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'Menu';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a navigation menu', 'menus');
    }

    public function getID(object $object): string | int | null
    {
        $menu = $object;
        return $this->menuTypeAPI->getMenuID($menu);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->menuTypeDataLoader;
    }
}

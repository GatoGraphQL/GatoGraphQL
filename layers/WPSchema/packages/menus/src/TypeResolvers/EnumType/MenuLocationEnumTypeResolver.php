<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class MenuLocationEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuLocationEnum';
    }

    public function getTypeDescription(): string
    {
        return $this->getTranslationAPI()->__('Menu Locations', 'menus');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys(\get_nav_menu_locations());
    }
}

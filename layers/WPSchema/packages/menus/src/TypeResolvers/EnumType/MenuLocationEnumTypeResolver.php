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
        // Make sure there's at least 1 result, to avoid GraphQL throwing
        // errors from an empty Enum
        if ($enumValues = array_keys(\get_registered_nav_menus())) {
            return $enumValues;
        }
        return ['empty'];
    }
}

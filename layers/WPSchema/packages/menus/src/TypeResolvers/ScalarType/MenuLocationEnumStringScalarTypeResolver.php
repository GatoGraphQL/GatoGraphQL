<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\TypeResolvers\ScalarType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;

class MenuLocationEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuLocationEnumString';
    }

    public function getEnumStringTypeDescription(): ?string
    {
        return $this->__('Menu Locations', 'menus');
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        return array_keys(\get_registered_nav_menus());
    }
}

<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\TypeResolvers\ScalarType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractSelectableStringScalarTypeResolver;

class MenuLocationSelectableStringTypeResolver extends AbstractSelectableStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuLocationSelectableString';
    }

    public function getTypeDescription(): string
    {
        return sprintf(
            $this->getTranslationAPI()->__('Menu Locations, with possible values: `"%s"`.', 'menus'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        return array_keys(\get_registered_nav_menus());
    }
}

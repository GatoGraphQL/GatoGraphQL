<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryValueTypes;

/**
 * Meta query "type", as explained here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
 */
class MetaQueryValueTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryValueTypesEnum';
    }

    public function getTypeDescription(): string
    {
        return $this->__('Custom field type', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryValueTypes::NUMERIC,
            MetaQueryValueTypes::BINARY,
            MetaQueryValueTypes::CHAR,
            MetaQueryValueTypes::DATE,
            MetaQueryValueTypes::DATETIME,
            MetaQueryValueTypes::DECIMAL,
            MetaQueryValueTypes::SIGNED,
            MetaQueryValueTypes::TIME,
            MetaQueryValueTypes::UNSIGNED,
        ];
    }
}

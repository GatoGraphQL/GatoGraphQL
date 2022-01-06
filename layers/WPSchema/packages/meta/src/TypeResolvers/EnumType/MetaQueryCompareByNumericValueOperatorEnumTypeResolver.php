<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;

/**
 * Documentation:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
 */
class MetaQueryCompareByNumericValueOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByNumericValueOperator';
    }

    public function getTypeDescription(): string
    {
        return $this->__('Operators to compare against a numeric value', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::EQUALS,
            MetaQueryCompareByOperators::NOT_EQUALS,
            MetaQueryCompareByOperators::GREATER_THAN,
            MetaQueryCompareByOperators::GREATER_THAN_OR_EQUAL,
            MetaQueryCompareByOperators::LESS_THAN,
            MetaQueryCompareByOperators::LESS_THAN_OR_EQUAL,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MetaQueryCompareByOperators::EQUALS => '\'=\'',
            MetaQueryCompareByOperators::NOT_EQUALS => '\'!=\'',
            MetaQueryCompareByOperators::GREATER_THAN => '\'>\'',
            MetaQueryCompareByOperators::GREATER_THAN_OR_EQUAL => '\'>=\'',
            MetaQueryCompareByOperators::LESS_THAN => '\'<\'',
            MetaQueryCompareByOperators::LESS_THAN_OR_EQUAL => '\'<=\'',
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}

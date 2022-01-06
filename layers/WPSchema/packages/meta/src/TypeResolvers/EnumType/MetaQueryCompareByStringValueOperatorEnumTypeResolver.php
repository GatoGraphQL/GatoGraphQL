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
class MetaQueryCompareByStringValueOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByStringValueOperator';
    }

    public function getTypeDescription(): string
    {
        return $this->__('Operators to compare against a string value', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::EQUALS,
            MetaQueryCompareByOperators::NOT_EQUALS,
            MetaQueryCompareByOperators::LIKE,
            MetaQueryCompareByOperators::NOT_LIKE,
            MetaQueryCompareByOperators::REGEXP,
            MetaQueryCompareByOperators::NOT_REGEXP,
            MetaQueryCompareByOperators::RLIKE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MetaQueryCompareByOperators::EQUALS => '\'=\'',
            MetaQueryCompareByOperators::NOT_EQUALS => '\'!=\'',
            MetaQueryCompareByOperators::LIKE => '\'LIKE\'',
            MetaQueryCompareByOperators::NOT_LIKE => '\'NOT LIKE\'',
            MetaQueryCompareByOperators::REGEXP => '\'REGEXP\'',
            MetaQueryCompareByOperators::NOT_REGEXP => '\'NOT REGEXP\'',
            MetaQueryCompareByOperators::RLIKE => '\'RLIKE\'',
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}

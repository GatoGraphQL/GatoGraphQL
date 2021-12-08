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
class MetaQueryCompareBySingleValueOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareBySingleValueOperator';
    }

    public function getTypeDescription(): string
    {
        return $this->getTranslationAPI()->__('Operators to compare against a single value', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::EQ,
            MetaQueryCompareByOperators::NOT_EQ,
            MetaQueryCompareByOperators::GT,
            MetaQueryCompareByOperators::GET,
            MetaQueryCompareByOperators::LT,
            MetaQueryCompareByOperators::LET,
            MetaQueryCompareByOperators::LIKE,
            MetaQueryCompareByOperators::NOT_LIKE,
            MetaQueryCompareByOperators::EXISTS,
            MetaQueryCompareByOperators::NOT_EXISTS,
            MetaQueryCompareByOperators::REGEXP,
            MetaQueryCompareByOperators::NOT_REGEXP,
            MetaQueryCompareByOperators::RLIKE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MetaQueryCompareByOperators::EQ => '\'=\'',
            MetaQueryCompareByOperators::NOT_EQ => '\'!=\'',
            MetaQueryCompareByOperators::GT => '\'>\'',
            MetaQueryCompareByOperators::GET => '\'>=\'',
            MetaQueryCompareByOperators::LT => '\'<\'',
            MetaQueryCompareByOperators::LET => '\'<=\'',
            MetaQueryCompareByOperators::LIKE => '\'LIKE\'',
            MetaQueryCompareByOperators::NOT_LIKE => '\'NOT LIKE\'',
            MetaQueryCompareByOperators::EXISTS => '\'EXISTS\'',
            MetaQueryCompareByOperators::NOT_EXISTS => '\'NOT EXISTS\'',
            MetaQueryCompareByOperators::REGEXP => '\'REGEXP\'',
            MetaQueryCompareByOperators::NOT_REGEXP => '\'NOT REGEXP\'',
            MetaQueryCompareByOperators::RLIKE => '\'RLIKE\'',
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}

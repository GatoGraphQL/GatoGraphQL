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
class MetaQueryCompareByKeyOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByKeyOperator';
    }

    public function getTypeDescription(): string
    {
        return $this->getTranslationAPI()->__('Operators to compare against the meta key', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::EXISTS,
            MetaQueryCompareByOperators::NOT_EXISTS,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MetaQueryCompareByOperators::EXISTS => '\'EXISTS\'',
            MetaQueryCompareByOperators::NOT_EXISTS => '\'NOT EXISTS\'',
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}

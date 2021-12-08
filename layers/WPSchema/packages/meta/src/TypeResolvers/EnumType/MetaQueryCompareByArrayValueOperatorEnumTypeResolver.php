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
class MetaQueryCompareByArrayValueOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByArrayValueOperator';
    }

    public function getTypeDescription(): string
    {
        return $this->getTranslationAPI()->__('Operators to compare against an array value', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::IN,
            MetaQueryCompareByOperators::NOT_IN,
            MetaQueryCompareByOperators::BETWEEN,
            MetaQueryCompareByOperators::NOT_BETWEEN,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MetaQueryCompareByOperators::IN => '\'IN\'',
            MetaQueryCompareByOperators::NOT_IN => '\'NOT IN\'',
            MetaQueryCompareByOperators::BETWEEN => '\'BETWEEN\'',
            MetaQueryCompareByOperators::NOT_BETWEEN => '\'NOT BETWEEN\'',
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}

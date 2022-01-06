<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\BasicService\AbstractHookSet;
use PoPWPSchema\Meta\Constants\MetaOrderBy;

abstract class AbstractMetaOrderByEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::ENUM_VALUES,
            [$this, 'getEnumValues'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::ENUM_VALUE_DESCRIPTION,
            [$this, 'getEnumValueDescription'],
            10,
            3
        );
    }

    /**
     * @param string[] $enumValues
     */
    public function getEnumValues(
        array $enumValues,
        EnumTypeResolverInterface $enumTypeResolver,
    ): array {
        if (!$this->isEnumTypeResolver($enumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                MetaOrderBy::META_VALUE,
            ]
        );
    }

    abstract protected function isEnumTypeResolver(
        EnumTypeResolverInterface $enumTypeResolver,
    ): bool;

    public function getEnumValueDescription(
        ?string $enumValueDescription,
        EnumTypeResolverInterface $enumTypeResolver,
        string $enumValue
    ): ?string {
        if (!$this->isEnumTypeResolver($enumTypeResolver)) {
            return $enumValueDescription;
        }
        return match ($enumValue) {
            MetaOrderBy::META_VALUE => $this->__('Order by meta value. See description for ‘meta_value‘ in: https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters', 'comments'),
            default => $enumValueDescription,
        };
    }
}

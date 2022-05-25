<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;

class CustomPostStatusFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;

    final public function setFilterCustomPostStatusEnumTypeResolver(FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver): void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        return $this->filterCustomPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
    }

    protected function getQueryArgKey(array $filterInput): string
    {
        return 'status';
    }

    /**
     * Remove any status that is not in the Enum
     */
    protected function getValue(mixed $value): mixed
    {
        return array_intersect(
            $value,
            $this->getFilterCustomPostStatusEnumTypeResolver()->getConsolidatedEnumValues()
        );
    }

    /**
     * If no status is valid, do not set, as to not override the default value
     */
    protected function avoidSettingValueIfEmpty(): bool
    {
        return true;
    }
}

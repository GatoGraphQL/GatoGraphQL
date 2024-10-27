<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;

class CustomPostStatusFilterInput extends AbstractValueToQueryFilterInput
{
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;

    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        if ($this->filterCustomPostStatusEnumTypeResolver === null) {
            /** @var FilterCustomPostStatusEnumTypeResolver */
            $filterCustomPostStatusEnumTypeResolver = $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
            $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
        }
        return $this->filterCustomPostStatusEnumTypeResolver;
    }

    protected function getQueryArgKey(): string
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

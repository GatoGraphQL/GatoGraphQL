<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;

class CustomPostStatusFilterInputProcessor extends AbstractFilterInputProcessor
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

    final public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        // Remove any status that is not in the Enum
        if ($value) {
            $value = array_intersect(
                $value,
                $this->getFilterCustomPostStatusEnumTypeResolver()->getConsolidatedEnumValues()
            );
            // If no status is valid, do not set, as to not override the default value
            if ($value) {
                $query['status'] = $value;
            }
        }
    }
}

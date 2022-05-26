<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;
use PoPCMSSchema\CustomPosts\FilterInput\FilterInputHelper;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class UnionCustomPostTypesFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'custompost-types';
    }

    /**
     * Make sure the provided postTypes are part of the UnionTypeResolver.
     * Otherwise it can create problem if querying for an existing postType (eg: "page")
     * when it hasn't been added to the UnionTypeResolver, because the ID will not be
     * qualified with the type, and cause an exception down the road
     */
    protected function getValue(mixed $value): mixed
    {
        $value = array_intersect(
            $value,
            CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes()
        );
        return FilterInputHelper::maybeGetNonExistingCustomPostTypes($value);
    }
}

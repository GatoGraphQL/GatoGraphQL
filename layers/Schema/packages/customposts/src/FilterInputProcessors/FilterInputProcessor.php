<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FilterInputProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\FilterInput\FilterInputHelper;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_CUSTOMPOSTDATES = 'filterinput-custompostdates';
    public const FILTERINPUT_CUSTOMPOSTTYPES = 'filterinput-customposttypes';
    public const FILTERINPUT_CUSTOMPOSTSTATUS = 'filterinput-custompoststatus';
    public const FILTERINPUT_UNIONCUSTOMPOSTTYPES = 'filterinput-unioncustomposttypes';
    protected CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver;

    #[Required]
    public function autowireFilterInputProcessor(
        CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver
    ) {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }


    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_CUSTOMPOSTDATES],
            [self::class, self::FILTERINPUT_CUSTOMPOSTTYPES],
            [self::class, self::FILTERINPUT_CUSTOMPOSTSTATUS],
            [self::class, self::FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_CUSTOMPOSTDATES:
                $query['date-from'] = $value['from'];
                $query['date-to'] = $value['to'];
                break;
            case self::FILTERINPUT_CUSTOMPOSTTYPES:
                $query['custompost-types'] = $value;
                break;
            case self::FILTERINPUT_CUSTOMPOSTSTATUS:
                // Remove any status that is not in the Enum
                if ($value) {
                    $value = array_intersect(
                        $value,
                        $this->customPostStatusEnumTypeResolver->getEnumValues()
                    );
                    // If no status is valid, do not set, as to not override the default value
                    if ($value) {
                        $query['status'] = $value;
                    }
                }
                break;
            case self::FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                // Make sure the provided postTypes are part of the UnionTypeResolver
                // Otherwise it can create problem if querying for an existing postType (eg: "page")
                // when it hasn't been added to the UnionTypeResolver, because the ID will not be
                // qualified with the type, and cause an exception down the road
                if ($value) {
                    $value = array_intersect(
                        $value,
                        CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes()
                    );
                    $value = FilterInputHelper::maybeGetNonExistingCustomPostTypes($value);
                }
                $query['custompost-types'] = $value;
                break;
        }
    }
}

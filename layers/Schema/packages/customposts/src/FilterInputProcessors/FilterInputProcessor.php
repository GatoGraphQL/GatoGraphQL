<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const NON_EXISTING_CUSTOM_POST_TYPE = 'non-existing-customp-post-type';

    public const FILTERINPUT_CUSTOMPOSTDATES = 'filterinput-custompostdates';
    public const FILTERINPUT_CUSTOMPOSTTYPES = 'filterinput-customposttypes';
    public const FILTERINPUT_CUSTOMPOSTSTATUS = 'filterinput-custompoststatus';
    public const FILTERINPUT_GENERICCUSTOMPOSTTYPES = 'filterinput-genericcustomposttypes';
    public const FILTERINPUT_UNIONCUSTOMPOSTTYPES = 'filterinput-unioncustomposttypes';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_CUSTOMPOSTDATES],
            [self::class, self::FILTERINPUT_CUSTOMPOSTTYPES],
            [self::class, self::FILTERINPUT_CUSTOMPOSTSTATUS],
            [self::class, self::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            [self::class, self::FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
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
                    /**
                     * @var CustomPostStatusEnum
                     */
                    $customPostStatusEnum = $this->instanceManager->getInstance(CustomPostStatusEnum::class);
                    $value = array_intersect(
                        $value,
                        $customPostStatusEnum->getValues()
                    );
                    // If no status is valid, do not set, as to not override the default value
                    if ($value) {
                        $query['status'] = $value;
                    }
                }
                break;
            case self::FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                // Make sure the provided postTypes have been whitelisted
                // Otherwise do not produce their IDs in first place
                if ($value) {
                    $value = array_intersect(
                        $value,
                        ComponentConfiguration::getGenericCustomPostTypes()
                    );
                    $value = $this->maybeGetNonExistingCustomPostTypes($value);
                }
                $query['custompost-types'] = $value;
                break;
            case self::FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                // Make sure the provided postTypes are part of the UnionTypeResolver
                // Otherwise it can create problem if querying for an existing postType (eg: "page")
                // when it hasn't been added to the UnionTypeResolver, because the ID will not be
                // qualified with the type, and cause an exception down the road
                if ($value) {
                    $value = array_intersect(
                        $value,
                        CustomPostUnionTypeHelpers::getTargetTypeResolverCustomPostTypes(
                            CustomPostUnionTypeResolver::class
                        )
                    );
                    $value = $this->maybeGetNonExistingCustomPostTypes($value);
                }
                $query['custompost-types'] = $value;
                break;
        }
    }

    /**
     * If there are no valid postTypes, then force the query to
     * return no results.
     * Otherwise, the query would return the results for post type "post"
     * (the default when postTypes is empty)
     */
    protected function maybeGetNonExistingCustomPostTypes(array $value): array
    {
        if (!$value) {
            // Array of non-existing IDs
            return [
                self::NON_EXISTING_CUSTOM_POST_TYPE,
            ];
        }
        return $value;
    }
}




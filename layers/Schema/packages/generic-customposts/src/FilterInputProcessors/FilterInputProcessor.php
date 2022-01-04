<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;
use PoPSchema\CustomPosts\FilterInput\FilterInputHelper;
use PoPSchema\GenericCustomPosts\Component;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_GENERICCUSTOMPOSTTYPES = 'filterinput-genericcustomposttypes';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                // Make sure the provided postTypes have been whitelisted
                // Otherwise do not produce their IDs in first place
                if ($value) {
                    $value = array_intersect(
                        $value,
                        ComponentConfiguration::getGenericCustomPostTypes()
                    );
                    $value = FilterInputHelper::maybeGetNonExistingCustomPostTypes($value);
                }
                $query['custompost-types'] = $value;
                break;
        }
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\FilterInputProcessors;

use PoP\Root\App;
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;
use PoPCMSSchema\CustomPosts\FilterInput\FilterInputHelper;
use PoPCMSSchema\GenericCustomPosts\Module;
use PoPCMSSchema\GenericCustomPosts\ComponentConfiguration;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_GENERICCUSTOMPOSTTYPES = 'filterinput-genericcustomposttypes';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        switch ($filterInput[1]) {
            case self::FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                // Make sure the provided postTypes have been whitelisted
                // Otherwise do not produce their IDs in first place
                if ($value) {
                    $value = array_intersect(
                        $value,
                        $componentConfiguration->getGenericCustomPostTypes()
                    );
                    $value = FilterInputHelper::maybeGetNonExistingCustomPostTypes($value);
                }
                $query['custompost-types'] = $value;
                break;
        }
    }
}

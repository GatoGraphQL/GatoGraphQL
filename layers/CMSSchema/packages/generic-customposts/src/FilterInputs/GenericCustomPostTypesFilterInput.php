<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\FilterInputs;

use PoP\Root\App;
use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;
use PoPCMSSchema\CustomPosts\FilterInput\FilterInputHelper;
use PoPCMSSchema\GenericCustomPosts\Module;
use PoPCMSSchema\GenericCustomPosts\ModuleConfiguration;

class GenericCustomPostTypesFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'custompost-types';
    }

    /**
     * Make sure the provided postTypes have been whitelisted.
     * Otherwise do not produce their IDs in first place
     */
    protected function getValue(mixed $value): mixed
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $value = array_intersect(
            $value,
            $moduleConfiguration->getGenericCustomPostTypes()
        );
        return FilterInputHelper::maybeGetNonExistingCustomPostTypes($value);
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Posts\ModuleProcessors\AbstractPostFilterInputContainerModuleProcessor;
use PoPCMSSchema\Tags\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractPostFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputModules(...)
        );
    }

    public function getFilterInputModules(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_TAG_SLUGS
            ],
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_TAG_IDS
            ],
        ];
    }
}

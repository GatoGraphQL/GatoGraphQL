<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\SchemaHooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\Posts\ModuleProcessors\AbstractPostFilterInputContainerModuleProcessor;
use PoPSchema\Tags\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            AbstractPostFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputModules']
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

<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\SchemaHooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\Categories\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\Posts\ModuleProcessors\AbstractPostFilterInputContainerModuleProcessor;

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
                FilterInputModuleProcessor::MODULE_FILTERINPUT_CATEGORY_IDS
            ],
        ];
    }
}

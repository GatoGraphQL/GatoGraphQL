<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\SchemaHooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Posts\ModuleProcessors\FilterInputContainerModuleProcessor;
use PoPSchema\Tags\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{    
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            FilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputSubmodules']
        );
    }

    public function getFilterInputSubmodules(array $filterInputSubmodules): array
    {
        return [
            ...$filterInputSubmodules,
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

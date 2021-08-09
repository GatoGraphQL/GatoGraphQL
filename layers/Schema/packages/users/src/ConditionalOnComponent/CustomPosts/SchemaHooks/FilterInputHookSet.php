<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Posts\ModuleProcessors\PostFilterInputContainerModuleProcessor;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{    
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            PostFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputSubmodules']
        );
    }

    public function getFilterInputSubmodules(array $filterInputSubmodules): array
    {
        return [
            ...$filterInputSubmodules,
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_AUTHOR_IDS
            ],
        ];
    }
}

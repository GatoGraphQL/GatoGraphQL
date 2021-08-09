<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\ModuleProcessors\AbstractCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\Users\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{    
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            AbstractCustomPostFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputSubmodules']
        );
    }

    public function getFilterInputSubmodules(array $filterInputSubmodules): array
    {
        return [
            ...$filterInputSubmodules,
            // [
            //     FilterInputModuleProcessor::class,
            //     FilterInputModuleProcessor::MODULE_FILTERINPUT_AUTHOR_IDS
            // ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\ModuleProcessors\AbstractCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputModules']
        );
    }

    public function getFilterInputModules(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            ...$this->getAuthorFilterInputModules(),
        ];
    }

    public function getAuthorFilterInputModules(): array
    {
        return [
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_AUTHOR_IDS
            ],
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_AUTHOR_SLUG
            ],
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS
            ],
        ];
    }
}

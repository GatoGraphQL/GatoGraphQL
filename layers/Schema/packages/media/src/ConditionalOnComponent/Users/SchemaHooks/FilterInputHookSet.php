<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\Media\ModuleProcessors\MediaFilterInputContainerModuleProcessor;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            MediaFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
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

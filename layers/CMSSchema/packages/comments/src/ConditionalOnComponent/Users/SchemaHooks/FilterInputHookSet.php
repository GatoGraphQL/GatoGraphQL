<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\ModuleProcessors\FormInputs\FilterInputModuleProcessor as UserFilterInputModuleProcessor;
use PoPCMSSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CommentFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputModules(...)
        );
    }

    public function getFilterInputModules(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            ...$this->getAuthorFilterInputModules(),
            ...$this->getCustomPostAuthorFilterInputModules(),
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
                FilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS
            ],
        ];
    }

    public function getCustomPostAuthorFilterInputModules(): array
    {
        return [
            [
                UserFilterInputModuleProcessor::class,
                UserFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS
            ],
            [
                UserFilterInputModuleProcessor::class,
                UserFilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
            ],
        ];
    }
}

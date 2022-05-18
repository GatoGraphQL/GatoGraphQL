<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\ComponentProcessors\FormInputs\FilterInputComponentProcessor as UserFilterInputComponentProcessor;
use PoPCMSSchema\Comments\ComponentProcessors\CommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CommentFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
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
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_AUTHOR_IDS
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS
            ],
        ];
    }

    public function getCustomPostAuthorFilterInputModules(): array
    {
        return [
            [
                UserFilterInputComponentProcessor::class,
                UserFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS
            ],
            [
                UserFilterInputComponentProcessor::class,
                UserFilterInputComponentProcessor::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
            ],
        ];
    }
}

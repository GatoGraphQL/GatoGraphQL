<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputModules(...)
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
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_AUTHOR_IDS
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_AUTHOR_SLUG
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS
            ],
        ];
    }
}

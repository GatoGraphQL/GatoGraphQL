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
            $this->getFilterInputComponents(...)
        );
    }

    public function getFilterInputComponents(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            ...$this->getAuthorFilterInputComponents(),
            ...$this->getCustomPostAuthorFilterInputComponents(),
        ];
    }

    public function getAuthorFilterInputComponents(): array
    {
        return [
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_AUTHOR_IDS
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS
            ],
        ];
    }

    public function getCustomPostAuthorFilterInputComponents(): array
    {
        return [
            [
                UserFilterInputComponentProcessor::class,
                UserFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS
            ],
            [
                UserFilterInputComponentProcessor::class,
                UserFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
            ],
        ];
    }
}

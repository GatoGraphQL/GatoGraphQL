<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ConditionalOnModule\Users\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Media\ComponentProcessors\MediaFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            MediaFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputComponents(...)
        );
    }

    public function getFilterInputComponents(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            ...$this->getAuthorFilterInputComponents(),
        ];
    }

    public function getAuthorFilterInputComponents(): array
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

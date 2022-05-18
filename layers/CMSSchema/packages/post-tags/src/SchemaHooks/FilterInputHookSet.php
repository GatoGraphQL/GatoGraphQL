<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Tags\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractPostFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputModules(...)
        );
    }

    public function getFilterInputModules(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_TAG_SLUGS
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_TAG_IDS
            ],
        ];
    }
}

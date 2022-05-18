<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Categories\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractPostFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputComponents(...)
        );
    }

    public function getFilterInputComponents(array $filterInputModules): array
    {
        return [
            ...$filterInputModules,
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CATEGORY_IDS
            ],
        ];
    }
}

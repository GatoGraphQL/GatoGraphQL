<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserRoles\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\UserFilterInputContainerComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            UserFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputComponents(...),
            10,
            2
        );
    }

    public function getFilterInputComponents(array $filterInputComponents, array $component): array
    {
        $adminComponentNames = [
            UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS,
            UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT,
        ];
        if (in_array($component[1], $adminComponentNames)) {
            return [
                ...$filterInputComponents,
                ...$this->getUserFilterInputComponents(),
            ];
        }
        return $filterInputComponents;
    }

    public function getUserFilterInputComponents(): array
    {
        return [
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_USER_ROLES
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES
            ],
        ];
    }
}

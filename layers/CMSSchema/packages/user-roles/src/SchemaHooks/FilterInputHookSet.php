<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\SchemaHooks;

use PoP\ComponentModel\Component\Component;
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

    /**
     * @param Component[] $filterInputComponents
     * @return Component[]
     */
    public function getFilterInputComponents(array $filterInputComponents, Component $component): array
    {
        $adminComponentNames = [
            UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS,
            UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT,
        ];
        if (in_array($component->name, $adminComponentNames)) {
            return [
                ...$filterInputComponents,
                ...$this->getUserFilterInputComponents(),
            ];
        }
        return $filterInputComponents;
    }

    /**
     * @return Component[]
     */
    public function getUserFilterInputComponents(): array
    {
        return [
            new Component(
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_USER_ROLES
            ),
            new Component(
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES
            ),
        ];
    }
}

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
            $this->getFilterInputComponentVariations(...),
            10,
            2
        );
    }

    public function getFilterInputComponentVariations(array $filterInputModules, array $module): array
    {
        $adminModuleNames = [
            UserFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERS,
            UserFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT,
        ];
        if (in_array($module[1], $adminModuleNames)) {
            return [
                ...$filterInputModules,
                ...$this->getUserFilterInputComponentVariations(),
            ];
        }
        return $filterInputModules;
    }

    public function getUserFilterInputComponentVariations(): array
    {
        return [
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_USER_ROLES
            ],
            [
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES
            ],
        ];
    }
}

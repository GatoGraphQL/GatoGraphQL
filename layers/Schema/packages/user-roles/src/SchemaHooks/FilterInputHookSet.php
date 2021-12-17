<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\SchemaHooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\UserRoles\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\Users\ModuleProcessors\UserFilterInputContainerModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            UserFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputModules'],
            10,
            2
        );
    }

    public function getFilterInputModules(array $filterInputModules, array $module): array
    {
        $adminModuleNames = [
            UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERS,
            UserFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT,
        ];
        if (in_array($module[1], $adminModuleNames)) {
            return [
                ...$filterInputModules,
                ...$this->getUserFilterInputModules(),
            ];
        }
        return $filterInputModules;
    }

    public function getUserFilterInputModules(): array
    {
        return [
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_USER_ROLES
            ],
            [
                FilterInputModuleProcessor::class,
                FilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_USER_ROLES
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ModuleProcessors;

use PoPCMSSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPCMSSchema\Users\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class UserFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_USERS = 'filterinputcontainer-users';
    public const MODULE_FILTERINPUTCONTAINER_USERCOUNT = 'filterinputcontainer-usercount';
    public const MODULE_FILTERINPUTCONTAINER_ADMINUSERS = 'filterinputcontainer-adminusers';
    public const MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT = 'filterinputcontainer-adminusercount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_USERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_USERCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINUSERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $userFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_NAME],
        ];
        $adminUserFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_EMAILS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_USERS => [
                ...$userFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERS => [
                ...$userFilterInputModules,
                ...$adminUserFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_USERCOUNT => $userFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT => [
                ...$userFilterInputModules,
                ...$adminUserFilterInputModules,
            ],
            default => [],
        };
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            self::HOOK_FILTER_INPUTS,
        ];
    }
}

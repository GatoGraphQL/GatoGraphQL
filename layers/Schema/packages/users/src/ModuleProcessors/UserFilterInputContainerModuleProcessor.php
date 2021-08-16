<?php

declare(strict_types=1);

namespace PoPSchema\Users\ModuleProcessors;

use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\Users\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

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
        $filterInputModules = match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_USERS,
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERS => [
                ...$userFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_USERCOUNT,
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT => $userFilterInputModules,
            default => [],
        };
        // The "email" is a restricted arg
        if (
            in_array($module[1], [
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERS,
            self::MODULE_FILTERINPUTCONTAINER_ADMINUSERCOUNT,
            ])
        ) {
            $filterInputModules[] = [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_EMAILS];
        }
        return $filterInputModules;
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

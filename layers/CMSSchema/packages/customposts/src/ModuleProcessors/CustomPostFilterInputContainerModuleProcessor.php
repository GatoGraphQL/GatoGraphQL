<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ModuleProcessors;

use PoPCMSSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class CustomPostFilterInputContainerModuleProcessor extends AbstractCustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST = 'filterinputcontainer-unioncustompostlist';
    public const MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT = 'filterinputcontainer-unioncustompostcount';
    public const MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST = 'filterinputcontainer-adminunioncustompostlist';
    public const MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT = 'filterinputcontainer-adminunioncustompostcount';
    public const MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST = 'filterinputcontainer-custompostlist';
    public const MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT = 'filterinputcontainer-custompostcount';
    public const MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST = 'filterinputcontainer-admincustompostlist';
    public const MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT = 'filterinputcontainer-admincustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $customPostFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
        ];
        $unionCustomPostFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        $adminCustomPostFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST => [
                ...$customPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST => [
                ...$customPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST => [
                ...$customPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST => [
                ...$customPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT => [
                ...$customPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT => [
                ...$customPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT => $customPostFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT => [
                ...$customPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
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

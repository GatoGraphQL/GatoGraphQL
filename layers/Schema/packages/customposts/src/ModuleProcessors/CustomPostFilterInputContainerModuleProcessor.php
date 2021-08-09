<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors;

use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class CustomPostFilterInputContainerModuleProcessor extends AbstractCustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST = 'filterinner-unioncustompostlist';
    public const MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT = 'filterinner-unioncustompostcount';
    public const MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTLIST = 'filterinner-adminunioncustompostlist';
    public const MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTCOUNT = 'filterinner-adminunioncustompostcount';
    public const MODULE_FILTERINNER_CUSTOMPOSTLISTLIST = 'filterinner-custompostlist';
    public const MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT = 'filterinner-custompostcount';
    public const MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST = 'filterinner-admincustompostlist';
    public const MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT = 'filterinner-admincustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST],
            [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT],
            [self::class, self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST],
            [self::class, self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $filterInputModules = match ($module[1]) {
            self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST,
            self::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTLIST,
            self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST,
            self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST =>
                [
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
                    [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                ],
                self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT,
                self::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTCOUNT,
                self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT,
                self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT =>
                [
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                ],
                default => [],
        };
        // Fields "customPosts" and "customPostCount" also have the "postTypes" filter
        if (
            in_array($module[1], [
                self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST,
                self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT,
            ])
        ) {
            $filterInputModules[] = [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES];
        }
        // "Admin" fields also have the "status" filter
        if (
            in_array($module[1], [
                self::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTLIST,
                self::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTCOUNT,
                self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST,
                self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT,
            ])
        ) {
            $filterInputModules[] = [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS];
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

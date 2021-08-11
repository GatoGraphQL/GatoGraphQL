<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\AbstractCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class PageFilterInputContainerModuleProcessor extends AbstractCustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINNER_PAGELISTLIST = 'filterinner-pagelist';
    public const MODULE_FILTERINNER_PAGELISTCOUNT = 'filterinner-pagecount';
    public const MODULE_FILTERINNER_ADMINPAGELISTLIST = 'filterinner-adminpagelist';
    public const MODULE_FILTERINNER_ADMINPAGELISTCOUNT = 'filterinner-adminpagecount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_PAGELISTLIST],
            [self::class, self::MODULE_FILTERINNER_PAGELISTCOUNT],
            [self::class, self::MODULE_FILTERINNER_ADMINPAGELISTLIST],
            [self::class, self::MODULE_FILTERINNER_ADMINPAGELISTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $filterInputModules = match ($module[1]) {
            self::MODULE_FILTERINNER_PAGELISTLIST,
            self::MODULE_FILTERINNER_ADMINPAGELISTLIST =>
                [
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
                    [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                ],
                self::MODULE_FILTERINNER_PAGELISTCOUNT,
                self::MODULE_FILTERINNER_ADMINPAGELISTCOUNT =>
                [
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                ],
                default => [],
        };
        // "Admin" fields also have the "status" filter
        if (
            in_array($module[1], [
                self::MODULE_FILTERINNER_ADMINPAGELISTLIST,
                self::MODULE_FILTERINNER_ADMINPAGELISTCOUNT,
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

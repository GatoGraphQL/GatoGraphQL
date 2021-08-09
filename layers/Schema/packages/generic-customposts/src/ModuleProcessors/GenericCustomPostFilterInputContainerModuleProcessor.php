<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\AbstractCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class GenericCustomPostFilterInputContainerModuleProcessor extends AbstractCustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST = 'filterinner-genericcustompostlist';
    public const MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT = 'filterinner-genericcustompostcount';
    public const MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTLIST = 'filterinner-admingenericcustompostlist';
    public const MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTCOUNT = 'filterinner-admingenericcustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $filterInputModules = match ($module[1]) {
            self::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST,
            self::MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTLIST
                => [
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
                    [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                    [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICPOSTTYPES],
                ],
            self::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT,
            self::MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTCOUNT =>
                [
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                    [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICPOSTTYPES],
                ],
                default => [],
        };
        // "Admin" fields also have the "status" filter
        if (
            in_array($module[1], [
                self::MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTLIST,
                self::MODULE_FILTERINNER_ADMINGENERICCUSTOMPOSTCOUNT,
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

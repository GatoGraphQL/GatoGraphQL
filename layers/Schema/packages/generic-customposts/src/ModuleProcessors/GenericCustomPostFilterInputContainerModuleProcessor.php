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

    public const MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST = 'filterinputcontainer-genericcustompostlist';
    public const MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT = 'filterinputcontainer-genericcustompostcount';
    public const MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST = 'filterinputcontainer-admingenericcustompostlist';
    public const MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT = 'filterinputcontainer-admingenericcustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $genericCustomPostFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICPOSTTYPES],
        ];
        $adminGenericCustomPostFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST=> [
                ...$genericCustomPostFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST => [
                    ...$genericCustomPostFilterInputModules,
                    ...$adminGenericCustomPostFilterInputModules,
                    ...$this->getPaginationFilterInputModules(),
                ],
            self::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT => $genericCustomPostFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT => [
                ...$genericCustomPostFilterInputModules,
                ...$adminGenericCustomPostFilterInputModules,
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

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoP\ComponentModel\FilterInput\FilterInputHelper;

class CommonCustomPostFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_AND_STATUS = 'filterinputcontainer-custompost-by-id-and-status';
    public const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_AND_STATUS = 'filterinputcontainer-custompost-by-slug-and-status';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_AND_STATUS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_AND_STATUS],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_AND_STATUS => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_AND_STATUS => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            default => [],
        };
    }

    public function getFieldDataFilteringMandatoryArgs(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_AND_STATUS:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID
                ]);
                return [
                    $idFilterInputName,
                ];
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_AND_STATUS:
                $slugFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG
                ]);
                return [
                    $slugFilterInputName,
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($module);
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

<?php

declare(strict_types=1);

namespace PoPSchema\Media\ModuleProcessors;

use PoPSchema\Media\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class MediaFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINNER_MEDIAITEMS = 'filterinner-media-items';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_MEDIAITEMS],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        return match ($module[1]) {
            self::MODULE_FILTERINNER_MEDIAITEMS => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_MIME_TYPES],
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

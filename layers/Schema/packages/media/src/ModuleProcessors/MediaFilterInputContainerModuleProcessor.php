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

    public const MODULE_FILTERINPUTCONTAINER_MEDIAITEMS = 'filterinputcontainer-media-items';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $mediaFilterInputModules = [
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_IDS],
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_MIME_TYPES],
        ];
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS => [
                ...$mediaFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
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

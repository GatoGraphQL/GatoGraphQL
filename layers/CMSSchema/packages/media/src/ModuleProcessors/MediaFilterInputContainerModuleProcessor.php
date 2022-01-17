<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ModuleProcessors;

use PoPCMSSchema\Media\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class MediaFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_MEDIAITEMS = 'filterinputcontainer-media-items';
    public const MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT = 'filterinputcontainer-media-item-count';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $mediaFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_MIME_TYPES],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS => [
                ...$mediaFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT => [
                ...$mediaFilterInputModules,
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

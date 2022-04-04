<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\AbstractFilterInputContainerModuleProcessor as UpstreamAbstractFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

abstract class AbstractFilterInputContainerModuleProcessor extends UpstreamAbstractFilterInputContainerModuleProcessor
{
    public final const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

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

    /**
     * @return array<array<mixed>>
     */
    protected function getPaginationFilterInputModules(): array
    {
        return [
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SORT],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getIDFilterInputModules(): array
    {
        return [
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_IDS],
        ];
    }
}

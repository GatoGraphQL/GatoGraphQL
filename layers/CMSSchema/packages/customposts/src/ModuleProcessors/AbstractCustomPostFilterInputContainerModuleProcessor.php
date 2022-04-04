<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ModuleProcessors;

use PoPCMSSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;

abstract class AbstractCustomPostFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
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
}

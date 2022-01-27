<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ModuleProcessors;

use PoPCMSSchema\Categories\ModuleProcessors\CategoryFilterInputContainerModuleProcessor;

class PostCategoryFilterInputContainerModuleProcessor extends CategoryFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

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

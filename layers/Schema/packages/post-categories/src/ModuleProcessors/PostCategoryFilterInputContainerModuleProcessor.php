<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ModuleProcessors;

use PoPSchema\Categories\ModuleProcessors\CategoryFilterInputContainerModuleProcessor;

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

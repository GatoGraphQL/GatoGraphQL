<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ModuleProcessors;

use PoPCMSSchema\Tags\ModuleProcessors\TagFilterInputContainerModuleProcessor;

class PostTagFilterInputContainerModuleProcessor extends TagFilterInputContainerModuleProcessor
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

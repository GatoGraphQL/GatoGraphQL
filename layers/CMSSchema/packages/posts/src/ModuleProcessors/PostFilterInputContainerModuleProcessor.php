<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ModuleProcessors;

class PostFilterInputContainerModuleProcessor extends AbstractPostFilterInputContainerModuleProcessor
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

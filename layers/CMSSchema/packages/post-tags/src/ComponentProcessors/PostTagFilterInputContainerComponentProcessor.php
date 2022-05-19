<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ComponentProcessors;

use PoPCMSSchema\Tags\ComponentProcessors\TagFilterInputContainerComponentProcessor;

class PostTagFilterInputContainerComponentProcessor extends TagFilterInputContainerComponentProcessor
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

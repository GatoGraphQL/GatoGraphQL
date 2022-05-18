<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;

abstract class AbstractCustomPostFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
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

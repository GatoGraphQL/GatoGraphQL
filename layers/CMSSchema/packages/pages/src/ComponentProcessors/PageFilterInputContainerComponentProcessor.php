<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ComponentProcessors;

class PageFilterInputContainerComponentProcessor extends AbstractPageFilterInputContainerComponentProcessor
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

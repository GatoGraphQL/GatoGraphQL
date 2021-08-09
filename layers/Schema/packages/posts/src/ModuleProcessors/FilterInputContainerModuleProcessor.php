<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ModuleProcessors;

class FilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
}

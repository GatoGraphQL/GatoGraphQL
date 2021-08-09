<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors;

interface CustomPostFilterInputContainerModuleProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
}

<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessorTrait;

class PostFilterInputContainerModuleProcessor extends AbstractPostFilterInputContainerModuleProcessor
{
    use CustomPostFilterInputContainerModuleProcessorTrait;

    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
}

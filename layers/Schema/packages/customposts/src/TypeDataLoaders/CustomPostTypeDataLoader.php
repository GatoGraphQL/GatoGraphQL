<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeDataLoaders;

use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInnerModuleProcessor;

class CustomPostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function getDataFilteringModule(): ?array
    {
        return [
            CustomPostFilterInnerModuleProcessor::class,
            CustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST
        ];
    }
}

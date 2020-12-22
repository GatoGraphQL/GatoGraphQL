<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeDataLoaders;

use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;

class CustomPostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function getFilterDataloadingModule(): ?array
    {
        return [
            CustomPostRelationalFieldDataloadModuleProcessor::class,
            CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST
        ];
    }
}

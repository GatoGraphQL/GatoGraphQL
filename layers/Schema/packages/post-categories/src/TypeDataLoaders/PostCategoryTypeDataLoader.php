<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeDataLoaders;

use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\Categories\TypeDataLoaders\AbstractCategoryTypeDataLoader;
use PoPSchema\PostCategories\ModuleProcessors\PostCategoryFieldDataloadModuleProcessor;

class PostCategoryTypeDataLoader extends AbstractCategoryTypeDataLoader
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getFilterDataloadingModule(): ?array
    {
        return [PostCategoryFieldDataloadModuleProcessor::class, PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST];
    }
}

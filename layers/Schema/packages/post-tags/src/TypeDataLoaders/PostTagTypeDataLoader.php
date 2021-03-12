<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeDataLoaders;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\TypeDataLoaders\AbstractTagTypeDataLoader;
use PoPSchema\PostTags\ModuleProcessors\PostTagFieldDataloadModuleProcessor;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;

    public function getFilterDataloadingModule(): ?array
    {
        return [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST];
    }
}

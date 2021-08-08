<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeDataLoaders;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\ModuleProcessors\FilterInnerModuleProcessor;
use PoPSchema\Tags\TypeDataLoaders\AbstractTagTypeDataLoader;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;

    public function getDataFilteringModule(): ?array
    {
        return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_TAGS];
    }
}

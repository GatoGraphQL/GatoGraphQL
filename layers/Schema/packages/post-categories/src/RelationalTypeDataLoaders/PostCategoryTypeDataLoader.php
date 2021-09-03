<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\RelationalTypeDataLoaders;

use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\Categories\RelationalTypeDataLoaders\AbstractCategoryTypeDataLoader;

class PostCategoryTypeDataLoader extends AbstractCategoryTypeDataLoader
{
    use PostCategoryAPISatisfiedContractTrait;
}

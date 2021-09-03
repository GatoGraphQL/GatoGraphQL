<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\RelationalTypeDataLoaders\Object;

use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\Categories\RelationalTypeDataLoaders\Object\AbstractCategoryTypeDataLoader;

class PostCategoryTypeDataLoader extends AbstractCategoryTypeDataLoader
{
    use PostCategoryAPISatisfiedContractTrait;
}

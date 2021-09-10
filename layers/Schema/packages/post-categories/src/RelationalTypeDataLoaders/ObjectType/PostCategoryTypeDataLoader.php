<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\Categories\RelationalTypeDataLoaders\ObjectType\AbstractCategoryTypeDataLoader;

class PostCategoryTypeDataLoader extends AbstractCategoryTypeDataLoader
{
    use PostCategoryAPISatisfiedContractTrait;
}

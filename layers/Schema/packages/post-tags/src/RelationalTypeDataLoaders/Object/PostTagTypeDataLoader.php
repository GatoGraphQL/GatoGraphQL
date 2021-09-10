<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagTypeDataLoader;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;
}

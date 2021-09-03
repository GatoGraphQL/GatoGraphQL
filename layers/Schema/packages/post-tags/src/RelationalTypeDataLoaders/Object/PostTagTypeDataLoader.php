<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\RelationalTypeDataLoaders\Object;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\RelationalTypeDataLoaders\Object\AbstractTagTypeDataLoader;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;
}

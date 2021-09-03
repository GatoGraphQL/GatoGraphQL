<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\RelationalTypeDataLoaders;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\RelationalTypeDataLoaders\AbstractTagTypeDataLoader;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;
}

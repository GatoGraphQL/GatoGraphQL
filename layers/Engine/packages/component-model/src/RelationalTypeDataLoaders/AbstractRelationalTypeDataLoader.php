<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\BasicService\BasicServiceTrait;
use PoP\LooseContracts\NameResolverInterface;

abstract class AbstractRelationalTypeDataLoader implements RelationalTypeDataLoaderInterface
{
    use BasicServiceTrait;

    private ?NameResolverInterface $nameResolver = null;

    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
}

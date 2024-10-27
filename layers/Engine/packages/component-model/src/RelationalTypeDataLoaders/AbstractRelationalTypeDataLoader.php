<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\Root\Services\BasicServiceTrait;
use PoP\LooseContracts\NameResolverInterface;

abstract class AbstractRelationalTypeDataLoader implements RelationalTypeDataLoaderInterface
{
    use BasicServiceTrait;

    private ?NameResolverInterface $nameResolver = null;

    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
    }
}

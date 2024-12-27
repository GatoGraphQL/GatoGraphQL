<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\Root\Services\AbstractBasicService;
use PoP\LooseContracts\NameResolverInterface;

abstract class AbstractRelationalTypeDataLoader extends AbstractBasicService implements RelationalTypeDataLoaderInterface
{
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

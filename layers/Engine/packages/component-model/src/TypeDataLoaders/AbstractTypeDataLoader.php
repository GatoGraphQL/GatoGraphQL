<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeDataLoaders;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractTypeDataLoader implements TypeDataLoaderInterface
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected NameResolverInterface $nameResolver,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRelationalTypeDataLoader implements RelationalTypeDataLoaderInterface
{
    protected HooksAPIInterface $hooksAPI;
    protected InstanceManagerInterface $instanceManager;
    protected NameResolverInterface $nameResolver;

    #[Required]
    public function autowireAbstractRelationalTypeDataLoader(HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, NameResolverInterface $nameResolver): void
    {
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
        $this->nameResolver = $nameResolver;
    }
}

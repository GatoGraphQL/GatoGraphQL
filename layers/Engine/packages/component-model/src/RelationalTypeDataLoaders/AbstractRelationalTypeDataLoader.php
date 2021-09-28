<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractRelationalTypeDataLoader implements RelationalTypeDataLoaderInterface
{
    protected HooksAPIInterface $hooksAPI;
    protected InstanceManagerInterface $instanceManager;
    protected NameResolverInterface $nameResolver;

    #[Required]
    public function autowireAbstractRelationalTypeDataLoader(HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, NameResolverInterface $nameResolver)
    {
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
        $this->nameResolver = $nameResolver;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRelationalTypeDataLoader implements RelationalTypeDataLoaderInterface
{
    use BasicServiceTrait;
    use WithHooksAPIServiceTrait;
    
    private ?NameResolverInterface $nameResolver = null;

    public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }

    //#[Required]
    final public function autowireAbstractRelationalTypeDataLoader(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\WithInstanceManagerServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRelationalTypeDataLoader implements RelationalTypeDataLoaderInterface
{
    use WithInstanceManagerServiceTrait;
    
    protected ?HooksAPIInterface $hooksAPI = null;
    protected ?NameResolverInterface $nameResolver = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->getInstanceManager()->getInstance(HooksAPIInterface::class);
    }
    public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->getInstanceManager()->getInstance(NameResolverInterface::class);
    }

    //#[Required]
    final public function autowireAbstractRelationalTypeDataLoader(HooksAPIInterface $hooksAPI, NameResolverInterface $nameResolver): void
    {
        $this->hooksAPI = $hooksAPI;
        $this->nameResolver = $nameResolver;
    }
}

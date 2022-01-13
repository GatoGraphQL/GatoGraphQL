<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Root\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?LooseContractManagerInterface $looseContractManager = null;
    private ?NameResolverInterface $nameResolver = null;

    final public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }
    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }

    final public function initialize(): void
    {
        $this->resolveContracts();
    }

    /**
     * Function to execute all code to satisfy the contracts
     */
    abstract protected function resolveContracts(): void;
}

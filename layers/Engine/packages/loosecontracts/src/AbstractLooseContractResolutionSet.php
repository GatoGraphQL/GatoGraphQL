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

    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        if ($this->looseContractManager === null) {
            /** @var LooseContractManagerInterface */
            $looseContractManager = $this->instanceManager->getInstance(LooseContractManagerInterface::class);
            $this->looseContractManager = $looseContractManager;
        }
        return $this->looseContractManager;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
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

<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\WithInstanceManagerServiceTrait;

abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    use WithInstanceManagerServiceTrait;

    private ?HooksAPIInterface $hooksAPI = null;
    private ?LooseContractManagerInterface $looseContractManager = null;
    private ?NameResolverInterface $nameResolver = null;

    final public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    final protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }
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

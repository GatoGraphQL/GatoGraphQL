<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    protected ?LooseContractManagerInterface $looseContractManager = null;
    protected ?NameResolverInterface $nameResolver = null;
    protected ?HooksAPIInterface $hooksAPI = null;

    public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    protected function getLooseContractManager(): LooseContractManagerInterface
    {
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }
    public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractLooseContractResolutionSet(LooseContractManagerInterface $looseContractManager, NameResolverInterface $nameResolver, HooksAPIInterface $hooksAPI): void
    {
        $this->looseContractManager = $looseContractManager;
        $this->nameResolver = $nameResolver;
        $this->hooksAPI = $hooksAPI;
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

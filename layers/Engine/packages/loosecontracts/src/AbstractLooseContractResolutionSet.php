<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    use WithHooksAPIServiceTrait;
    use WithInstanceManagerServiceTrait;

    private ?LooseContractManagerInterface $looseContractManager = null;
    private ?NameResolverInterface $nameResolver = null;

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

    final public function initialize(): void
    {
        $this->resolveContracts();
    }

    /**
     * Function to execute all code to satisfy the contracts
     */
    abstract protected function resolveContracts(): void;
}

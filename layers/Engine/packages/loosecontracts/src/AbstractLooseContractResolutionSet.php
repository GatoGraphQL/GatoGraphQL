<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    protected LooseContractManagerInterface $looseContractManager;
    protected NameResolverInterface $nameResolver;
    protected HooksAPIInterface $hooksAPI;

    public function __construct(
        LooseContractManagerInterface $looseContractManager,
        NameResolverInterface $nameResolver,
        HooksAPIInterface $hooksAPI
    ) {
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
     *
     * @return void
     */
    abstract protected function resolveContracts();
}

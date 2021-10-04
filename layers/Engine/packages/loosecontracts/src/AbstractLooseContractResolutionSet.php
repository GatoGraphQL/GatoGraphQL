<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    protected LooseContractManagerInterface $looseContractManager;
    protected NameResolverInterface $nameResolver;
    protected HooksAPIInterface $hooksAPI;

    #[Required]
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

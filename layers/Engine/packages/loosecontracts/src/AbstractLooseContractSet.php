<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\WithInstanceManagerServiceTrait;

abstract class AbstractLooseContractSet extends AbstractAutomaticallyInstantiatedService
{
    use WithInstanceManagerServiceTrait;

    private ?LooseContractManagerInterface $looseContractManager = null;

    final public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }

    public function initialize(): void
    {
        $this->getLooseContractManager()->requireNames(
            $this->getRequiredNames()
        );
    }
    
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [];
    }
}

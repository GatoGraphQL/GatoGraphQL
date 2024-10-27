<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\WithInstanceManagerServiceTrait;

abstract class AbstractLooseContractSet extends AbstractAutomaticallyInstantiatedService
{
    use WithInstanceManagerServiceTrait;

    private ?LooseContractManagerInterface $looseContractManager = null;

    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        if ($this->looseContractManager === null) {
            /** @var LooseContractManagerInterface */
            $looseContractManager = $this->instanceManager->getInstance(LooseContractManagerInterface::class);
            $this->looseContractManager = $looseContractManager;
        }
        return $this->looseContractManager;
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

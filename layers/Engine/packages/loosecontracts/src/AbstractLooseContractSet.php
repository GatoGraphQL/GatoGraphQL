<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractLooseContractSet extends AbstractAutomaticallyInstantiatedService
{
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

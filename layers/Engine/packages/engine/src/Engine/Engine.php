<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use PoP\ComponentModel\Engine\Engine as UpstreamEngine;
use PoP\Engine\Exception\ContractNotSatisfiedException;
use PoP\LooseContracts\LooseContractManagerInterface;

class Engine extends UpstreamEngine
{
    private ?LooseContractManagerInterface $looseContractManager = null;

    final public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        if ($this->looseContractManager === null) {
            /** @var LooseContractManagerInterface */
            $looseContractManager = $this->instanceManager->getInstance(LooseContractManagerInterface::class);
            $this->looseContractManager = $looseContractManager;
        }
        return $this->looseContractManager;
    }

    protected function generateData(): void
    {
        // Check if there are loose contracts that must be implemented by the CMS, that have not been done so.
        if ($notImplementedNames = $this->getLooseContractManager()->getNotImplementedRequiredNames()) {
            throw new ContractNotSatisfiedException(
                sprintf(
                    $this->__('The following names have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->__('", "'), $notImplementedNames)
                )
            );
        }

        parent::generateData();
    }
}

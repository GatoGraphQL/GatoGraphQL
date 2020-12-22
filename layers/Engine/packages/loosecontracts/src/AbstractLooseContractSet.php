<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

abstract class AbstractLooseContractSet
{
    protected LooseContractManagerInterface $looseContractManager;

    public function __construct(
        LooseContractManagerInterface $looseContractManager
    ) {
        $this->looseContractManager = $looseContractManager;

        // Require the configured hooks and names
        $this->looseContractManager->requireHooks(
            $this->getRequiredHooks()
        );
        $this->looseContractManager->requireNames(
            $this->getRequiredNames()
        );
    }

    /**
     * @return string[]
     */
    public function getRequiredHooks(): array
    {
        return [];
    }
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [];
    }
}

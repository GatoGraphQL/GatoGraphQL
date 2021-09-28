<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractLooseContractSet extends AbstractAutomaticallyInstantiatedService
{
    protected LooseContractManagerInterface $looseContractManager;

    #[Required]
    public function autowireAbstractLooseContractSet(LooseContractManagerInterface $looseContractManager)
    {
        $this->looseContractManager = $looseContractManager;
    }

    final public function initialize(): void
    {
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

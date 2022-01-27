<?php

declare(strict_types=1);

namespace LegacyPoP\LooseContracts;

use PoP\Root\Services\WithInstanceManagerServiceTrait;

abstract class AbstractLooseContractSet extends \PoP\LooseContracts\AbstractLooseContractSet
{
    use WithInstanceManagerServiceTrait;

    public function initialize(): void
    {
        parent::initialize();

        /** @var LegacyPoP\LooseContracts\LooseContractManagerInterface */
        $looseContractManager = $this->getLooseContractManager();
        $looseContractManager->requireHooks(
            $this->getRequiredHooks()
        );
    }

    /**
     * @return string[]
     */
    public function getRequiredHooks(): array
    {
        return [];
    }
}

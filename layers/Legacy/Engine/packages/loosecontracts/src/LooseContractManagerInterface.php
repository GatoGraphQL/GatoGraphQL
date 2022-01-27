<?php

declare(strict_types=1);

namespace LegacyPoP\LooseContracts;

interface LooseContractManagerInterface extends \PoP\LooseContracts\LooseContractManagerInterface
{
    /**
     * @return string[]
     */
    public function getNotImplementedRequiredHooks(): array;
    /**
     * @param string[] $hooks
     */
    public function requireHooks(array $hooks): void;
    /**
     * @param string[] $hooks
     */
    public function implementHooks(array $hooks): void;
}

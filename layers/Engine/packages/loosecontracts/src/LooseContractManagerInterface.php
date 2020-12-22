<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

interface LooseContractManagerInterface
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
    /**
     * @return string[]
     */
    public function getNotImplementedRequiredNames(): array;
    /**
     * @param string[] $names
     */
    public function requireNames(array $names): void;
    /**
     * @param string[] $names
     */
    public function implementNames(array $names): void;
}

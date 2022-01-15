<?php

declare(strict_types=1);

namespace LegacyPoP\LooseContracts;

class LooseContractManager extends \PoP\LooseContracts\LooseContractManager implements LooseContractManagerInterface
{
    /**
     * @var string[]
     */
    protected array $requiredHooks = [];
    /**
     * @var string[]
     */
    protected array $implementedHooks = [];

    /**
     * @return string[]
     */
    public function getNotImplementedRequiredHooks(): array
    {
        return array_diff(
            $this->requiredHooks,
            $this->implementedHooks
        );
    }

    /**
     * @param string[] $hooks
     */
    public function requireHooks(array $hooks): void
    {
        $this->requiredHooks = array_merge(
            $this->requiredHooks,
            $hooks
        );
    }

    /**
     * @param string[] $hooks
     */
    public function implementHooks(array $hooks): void
    {
        $this->implementedHooks = array_merge(
            $this->implementedHooks,
            $hooks
        );
    }
}

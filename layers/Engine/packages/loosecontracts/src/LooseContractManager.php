<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

class LooseContractManager implements LooseContractManagerInterface
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
     * @var string[]
     */
    protected array $requiredNames = [];
    /**
     * @var string[]
     */
    protected array $implementedNames = [];

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

    /**
     * @return string[]
     */
    public function getNotImplementedRequiredNames(): array
    {
        return array_diff(
            $this->requiredNames,
            $this->implementedNames
        );
    }

    /**
     * @param string[] $names
     */
    public function requireNames(array $names): void
    {
        $this->requiredNames = array_merge(
            $this->requiredNames,
            $names
        );
    }

    /**
     * @param string[] $names
     */
    public function implementNames(array $names): void
    {
        $this->implementedNames = array_merge(
            $this->implementedNames,
            $names
        );
    }
}

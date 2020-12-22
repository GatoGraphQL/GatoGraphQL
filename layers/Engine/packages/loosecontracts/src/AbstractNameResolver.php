<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

abstract class AbstractNameResolver implements NameResolverInterface
{
    protected LooseContractManagerInterface $looseContractManager;

    public function __construct(
        LooseContractManagerInterface $looseContractManager
    ) {
        $this->looseContractManager = $looseContractManager;
    }

    public function implementName(string $abstractName, string $implementationName): void
    {
        $this->looseContractManager->implementNames([$abstractName]);
    }

    /**
     * @param string[] $names
     */
    public function implementNames(array $names): void
    {
        $this->looseContractManager->implementNames(array_keys($names));
    }
}

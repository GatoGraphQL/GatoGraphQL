<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

abstract class AbstractNameResolver implements NameResolverInterface
{
    public function __construct(protected LooseContractManagerInterface $looseContractManager)
    {
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

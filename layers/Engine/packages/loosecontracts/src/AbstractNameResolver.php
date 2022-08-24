<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Root\Services\WithInstanceManagerServiceTrait;

abstract class AbstractNameResolver implements NameResolverInterface
{
    use WithInstanceManagerServiceTrait;

    private ?LooseContractManagerInterface $looseContractManager = null;

    final public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        /** @var LooseContractManagerInterface */
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }

    public function implementName(string $abstractName, string $implementationName): void
    {
        $this->getLooseContractManager()->implementNames([$abstractName]);
    }

    /**
     * @param string[] $names
     */
    public function implementNames(array $names): void
    {
        $this->getLooseContractManager()->implementNames(array_keys($names));
    }
}

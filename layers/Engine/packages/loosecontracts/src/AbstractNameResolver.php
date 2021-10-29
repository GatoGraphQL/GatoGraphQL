<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Root\Services\WithInstanceManagerServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractNameResolver implements NameResolverInterface
{
    use WithInstanceManagerServiceTrait;

    private ?LooseContractManagerInterface $looseContractManager = null;

    public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    protected function getLooseContractManager(): LooseContractManagerInterface
    {
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }

    //#[Required]
    final public function autowireAbstractNameResolver(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
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

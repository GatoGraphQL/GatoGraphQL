<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolution;

use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;

interface MutationResolutionStoreInterface
{
    public function clearResults(): void;

    public function setResult(ComponentMutationResolverBridgeInterface $componentMutationResolverBridge, mixed $result): void;

    public function getResult(ComponentMutationResolverBridgeInterface $componentMutationResolverBridge): mixed;
}

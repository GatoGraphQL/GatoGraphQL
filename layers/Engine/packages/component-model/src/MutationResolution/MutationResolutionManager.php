<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolution;

use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\BasicService\BasicServiceTrait;

class MutationResolutionManager implements MutationResolutionManagerInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string, mixed>
     */
    private array $results = [];

    public function clearResults(): void
    {
        $this->results = [];
    }

    public function setResult(ComponentMutationResolverBridgeInterface $componentMutationResolverBridge, mixed $result): void
    {
        $this->results[get_class($componentMutationResolverBridge)] = $result;
    }

    public function getResult(ComponentMutationResolverBridgeInterface $componentMutationResolverBridge): mixed
    {
        return $this->results[get_class($componentMutationResolverBridge)];
    }
}

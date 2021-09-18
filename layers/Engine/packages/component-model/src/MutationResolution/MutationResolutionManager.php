<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolution;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\Hooks\HooksAPIInterface;

class MutationResolutionManager implements MutationResolutionManagerInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $results = [];

    public function __construct(
        HooksAPIInterface $hooksAPI
    ) {
        $hooksAPI->addAction(
            'augmentVarsProperties',
            [$this, 'clearResults']
        );
    }

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

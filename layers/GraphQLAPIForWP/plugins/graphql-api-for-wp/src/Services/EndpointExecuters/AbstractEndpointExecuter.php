<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractEndpointExecuter implements EndpointExecuterInterface
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
        protected ModuleRegistryInterface $moduleRegistry,
    ) {
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->moduleRegistry->isModuleEnabled($enablingModule);
        }
        return true;
    }
}

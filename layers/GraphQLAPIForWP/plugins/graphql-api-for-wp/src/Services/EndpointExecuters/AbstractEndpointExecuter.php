<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractEndpointExecuter implements EndpointExecuterInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
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
        /**
         * Only initialize once, for the main AppThread
         */
        if (App::getAppThread()->getName() !== PluginAppGraphQLServerNames::EXTERNAL) {
            return false;
        }

        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null && !$this->getModuleRegistry()->isModuleEnabled($enablingModule)) {
            return false;
        }

        // Check the expected ?view=... is requested
        if (!$this->isEndpointBeingRequested()) {
            return false;
        }

        return true;
    }
}

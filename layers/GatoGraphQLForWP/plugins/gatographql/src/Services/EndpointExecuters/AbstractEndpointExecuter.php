<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractEndpointExecuter implements EndpointExecuterInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
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
        if (!AppHelpers::isMainAppThread()) {
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

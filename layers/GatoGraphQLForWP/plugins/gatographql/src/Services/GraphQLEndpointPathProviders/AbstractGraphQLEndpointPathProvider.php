<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;

abstract class AbstractGraphQLEndpointPathProvider extends AbstractAutomaticallyInstantiatedService implements GraphQLEndpointPathProviderInterface
{
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserSettingsManagerInterface $userSettingsManager = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule);
        }
        return parent::isServiceEnabled();
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialExtensionActivationServiceInterface;
use GatoGraphQL\GatoGraphQL\Registries\MarketplaceProviderCommercialExtensionActivationServiceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterMarketplaceProviderCommercialExtensionActivationServiceCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return MarketplaceProviderCommercialExtensionActivationServiceRegistryInterface::class;
    }

    protected function getServiceClass(): string
    {
        return MarketplaceProviderCommercialExtensionActivationServiceInterface::class;
    }

    protected function getRegistryMethodCallName(): string
    {
        return 'addMarketplaceProviderCommercialExtensionActivationService';
    }
}

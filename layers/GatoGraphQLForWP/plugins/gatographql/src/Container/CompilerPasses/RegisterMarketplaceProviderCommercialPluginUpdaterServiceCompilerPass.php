<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialPluginUpdaterServiceInterface;
use GatoGraphQL\GatoGraphQL\Registries\MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterMarketplaceProviderCommercialPluginUpdaterServiceCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface::class;
    }

    protected function getServiceClass(): string
    {
        return MarketplaceProviderCommercialPluginUpdaterServiceInterface::class;
    }

    protected function getRegistryMethodCallName(): string
    {
        return 'addMarketplaceProviderCommercialPluginUpdaterService';
    }
}

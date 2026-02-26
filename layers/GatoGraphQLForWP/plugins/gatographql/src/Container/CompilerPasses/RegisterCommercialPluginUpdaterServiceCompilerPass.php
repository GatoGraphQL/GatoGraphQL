<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialPluginUpdaterServiceInterface;
use GatoGraphQL\GatoGraphQL\Registries\CommercialPluginUpdaterServiceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterCommercialPluginUpdaterServiceCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CommercialPluginUpdaterServiceRegistryInterface::class;
    }

    protected function getServiceClass(): string
    {
        return MarketplaceProviderCommercialPluginUpdaterServiceInterface::class;
    }

    protected function getRegistryMethodCallName(): string
    {
        return 'addCommercialPluginUpdaterService';
    }
}

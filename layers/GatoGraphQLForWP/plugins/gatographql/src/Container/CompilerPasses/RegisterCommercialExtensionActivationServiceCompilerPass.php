<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialExtensionActivationServiceInterface;
use GatoGraphQL\GatoGraphQL\Registries\CommercialExtensionActivationServiceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterCommercialExtensionActivationServiceCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CommercialExtensionActivationServiceRegistryInterface::class;
    }

    protected function getServiceClass(): string
    {
        return MarketplaceProviderCommercialExtensionActivationServiceInterface::class;
    }

    protected function getRegistryMethodCallName(): string
    {
        return 'addCommercialExtensionActivationService';
    }
}


<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\TaxonomyRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\TaxonomyInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterGraphQLEndpointPathProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return TaxonomyRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return TaxonomyInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addTaxonomy';
    }
}

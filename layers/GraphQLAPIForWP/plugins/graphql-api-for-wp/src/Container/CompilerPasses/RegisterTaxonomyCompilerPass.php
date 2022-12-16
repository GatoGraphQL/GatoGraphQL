<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\TaxonomyRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterTaxonomyCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
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

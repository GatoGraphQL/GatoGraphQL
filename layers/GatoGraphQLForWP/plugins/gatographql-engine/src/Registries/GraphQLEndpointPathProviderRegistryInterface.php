<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders\GraphQLEndpointPathProviderInterface;

interface GraphQLEndpointPathProviderRegistryInterface
{
    public function addGraphQLEndpointPathProvider(GraphQLEndpointPathProviderInterface $graphQLEndpointPathProvider, string $serviceDefinitionID): void;

    /**
     * @return GraphQLEndpointPathProviderInterface[]
     */
    public function getGraphQLEndpointPathProviders(?bool $isPublic = null): array;
}

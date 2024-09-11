<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders\GraphQLEndpointPathProviderInterface;

class GraphQLEndpointPathProviderRegistry implements GraphQLEndpointPathProviderRegistryInterface
{
    /**
     * @var GraphQLEndpointPathProviderInterface[]
     */
    protected array $graphQLEndpointPathProviders = [];

    public function addGraphQLEndpointPathProvider(GraphQLEndpointPathProviderInterface $graphQLEndpointPathProvider, string $serviceDefinitionID): void
    {
        $this->graphQLEndpointPathProviders[] = $graphQLEndpointPathProvider;
    }

    /**
     * @return GraphQLEndpointPathProviderInterface[]
     */
    public function getGraphQLEndpointPathProviders(?bool $isPublic = null): array
    {
        if ($isPublic !== null) {
            return array_values(array_filter(
                $this->graphQLEndpointPathProviders,
                fn (GraphQLEndpointPathProviderInterface $graphQLEndpointPathProvider) => ($isPublic && $graphQLEndpointPathProvider->isPublic()) || (!$isPublic && !$graphQLEndpointPathProvider->isPublic())
            ));
        }
        return $this->graphQLEndpointPathProviders;
    }
}

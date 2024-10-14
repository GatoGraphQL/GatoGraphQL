<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Request;

use GatoGraphQL\GatoGraphQL\Registries\GraphQLEndpointPathProviderRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders\GraphQLEndpointPathProviderInterface;
use PoPAPI\APIEndpoints\EndpointUtils;
use PoP\Root\Routing\RoutingHelperServiceInterface;
use PoP\Root\Services\BasicServiceTrait;

/**
 * Use:
 *
 *   curl -i \
 *     --user "{ USER }:{ APPLICATION PASSWORD}" \
 *     -X POST \
 *     -H "Content-Type: application/json" \
 *     -d '{"query": "{ id me { name } }"}' \
 *     https://mysite.com/graphql/
 */
class PrematureRequestService implements PrematureRequestServiceInterface
{
    use BasicServiceTrait;

    private ?GraphQLEndpointPathProviderRegistryInterface $graphQLEndpointPathProviderRegistry = null;
    private ?RoutingHelperServiceInterface $routingHelperService = null;

    final public function setGraphQLEndpointPathProviderRegistry(GraphQLEndpointPathProviderRegistryInterface $graphQLEndpointPathProviderRegistry): void
    {
        $this->graphQLEndpointPathProviderRegistry = $graphQLEndpointPathProviderRegistry;
    }
    final protected function getGraphQLEndpointPathProviderRegistry(): GraphQLEndpointPathProviderRegistryInterface
    {
        if ($this->graphQLEndpointPathProviderRegistry === null) {
            /** @var GraphQLEndpointPathProviderRegistryInterface */
            $graphQLEndpointPathProviderRegistry = $this->instanceManager->getInstance(GraphQLEndpointPathProviderRegistryInterface::class);
            $this->graphQLEndpointPathProviderRegistry = $graphQLEndpointPathProviderRegistry;
        }
        return $this->graphQLEndpointPathProviderRegistry;
    }
    final public function setRoutingHelperService(RoutingHelperServiceInterface $routingHelperService): void
    {
        $this->routingHelperService = $routingHelperService;
    }
    final protected function getRoutingHelperService(): RoutingHelperServiceInterface
    {
        if ($this->routingHelperService === null) {
            /** @var RoutingHelperServiceInterface */
            $routingHelperService = $this->instanceManager->getInstance(RoutingHelperServiceInterface::class);
            $this->routingHelperService = $routingHelperService;
        }
        return $this->routingHelperService;
    }

    /**
     * Check if requesting a GraphQL endpoint.
     *
     * Because the AppStateProviders have not been initialized yet,
     * we can't check ->doingJSON().
     *
     * As a workaround, retrieve the configuration for all GraphQL endpoints
     * (Single endpoint, custom endpoint, and persisted queries) and,
     * if any of them is enabled, check if the URL starts with their
     * path (even if that specific endpoint is disabled).
     *
     * Notice this checks only for the publicly-exposed GraphQL
     * endpoints (i.e. not for `wp-admin/edit.php?page=gatographql&action=execute_query`
     * or any of those).
     */
    public function isPubliclyExposedGraphQLAPIRequest(): bool
    {
        /**
         * Check if the (slashed) requested URL starts with any
         * of the (slashed) GraphQL endpoints.
         *
         * Use `getRequestURI` as to remove the language info from
         * the URI when in subfolder-based Multisite.
         */
        $requestURI = $this->getRoutingHelperService()->getRequestURI() ?? '';
        $requestURI = EndpointUtils::removeMarkersFromURI($requestURI);
        $requestURI = EndpointUtils::slashURI($requestURI);
        foreach ($this->getGraphQLEndpointPaths() as $graphQLEndpointPath) {
            $graphQLEndpointPath = EndpointUtils::slashURI($graphQLEndpointPath);
            if (str_starts_with($requestURI, $graphQLEndpointPath)) {
                return true;
            }
        }
        return false;
    }

    /**
     * GraphQL endpoint paths (if enabled):
     *
     * - Single endpoint
     * - Custom endpoints
     * - Persisted query endpoints
     *
     * @return string[]
     */
    protected function getGraphQLEndpointPaths(): array
    {
        return array_map(
            fn (GraphQLEndpointPathProviderInterface $graphQLEndpointPathProvider) => $graphQLEndpointPathProvider->getPath(),
            $this->getGraphQLEndpointPathProviderRegistry()->getGraphQLEndpointPathProviders(true)
        );
    }
}

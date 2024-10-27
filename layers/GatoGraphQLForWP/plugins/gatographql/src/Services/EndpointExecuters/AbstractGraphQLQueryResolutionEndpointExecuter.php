<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use WP_Post;

abstract class AbstractGraphQLQueryResolutionEndpointExecuter extends AbstractCPTEndpointExecuter implements GraphQLQueryResolutionEndpointExecuterInterface, EndpointExecuterServiceTagInterface
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
    private ?QueryRetrieverInterface $queryRetriever = null;

    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        if ($this->graphQLDataStructureFormatter === null) {
            /** @var GraphQLDataStructureFormatter */
            $graphQLDataStructureFormatter = $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
            $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
        }
        return $this->graphQLDataStructureFormatter;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        if ($this->queryRetriever === null) {
            /** @var QueryRetrieverInterface */
            $queryRetriever = $this->instanceManager->getInstance(QueryRetrieverInterface::class);
            $this->queryRetriever = $queryRetriever;
        }
        return $this->queryRetriever;
    }

    protected function getView(): string
    {
        return '';
    }

    public function executeEndpoint(): void
    {
        // Nothing to do, required application state already set
        // in the corresponding AppStateProvider
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        // If null, we are in the admin (eg: editing a Persisted Query),
        // and there's no need to override params
        return $customPost !== null;
    }
}

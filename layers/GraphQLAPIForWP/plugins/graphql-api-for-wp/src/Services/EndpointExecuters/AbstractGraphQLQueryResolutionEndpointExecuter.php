<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverTrait;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet as GraphQLRequestVarsHookSet;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use WP_Post;

abstract class AbstractGraphQLQueryResolutionEndpointExecuter extends AbstractEndpointExecuter implements GraphQLQueryResolutionEndpointExecuterInterface
{
    use EndpointResolverTrait;

    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
    private ?QueryRetrieverInterface $queryRetriever = null;
    private ?GraphQLRequestVarsHookSet $graphQLRequestVarsHookSet = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    final public function setQueryRetriever(QueryRetrieverInterface $queryRetriever): void
    {
        $this->queryRetriever = $queryRetriever;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        return $this->queryRetriever ??= $this->instanceManager->getInstance(QueryRetrieverInterface::class);
    }
    final public function setGraphQLRequestVarsHookSet(GraphQLRequestVarsHookSet $graphQLRequestVarsHookSet): void
    {
        $this->graphQLRequestVarsHookSet = $graphQLRequestVarsHookSet;
    }
    final protected function getGraphQLRequestVarsHookSet(): GraphQLRequestVarsHookSet
    {
        return $this->graphQLRequestVarsHookSet ??= $this->instanceManager->getInstance(GraphQLRequestVarsHookSet::class);
    }

    protected function getView(): string
    {
        return '';
    }

    public function executeEndpoint(): void
    {
        $this->executeGraphQLQuery();
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

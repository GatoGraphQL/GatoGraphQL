<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\State;

use GraphQLByPoP\GraphQLRequest\StaticHelpers\GraphQLQueryPayloadRetriever;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    public function initialize(array &$state): void
    {
        $state['standard-graphql'] = true;
    }

    public function consolidate(array &$state): void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        // Single endpoint, starting at the Root object
        $state['nature'] = RequestNature::QUERY_ROOT;

        // Get the GraphQL payload from POST
        $payload = GraphQLQueryPayloadRetriever::getGraphQLQueryPayload();
        if ($payload === null) {
            return;
        }

        // Set state with the GraphQL query from the body
        $state['query'] = $payload['query'] ?? null;
        $state['variables'] = $payload['variables'] ?? [];
        $state['operation-name'] = $payload['operationName'] ?? null;
    }

    public function augment(array &$state): void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        $state['standard-graphql'] = true;

        // Do not include the fieldArgs and directives when outputting the field
        $state['only-fieldname-as-outputkey'] = true;
    }
}

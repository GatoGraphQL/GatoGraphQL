<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\State;

use GraphQLByPoP\GraphQLRequest\StaticHelpers\GraphQLQueryPayloadRetriever;
use PoP\API\Response\Schemes as APISchemes;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
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

    public function consolidate(array &$state): void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        // Override with the GraphQL query from the body
        $payload = GraphQLQueryPayloadRetriever::getGraphQLQueryPayload();
        $state['query'] = $payload['query'] ?? null;
        $state['variables'] = $payload['variables'] ?? null;
    }
}

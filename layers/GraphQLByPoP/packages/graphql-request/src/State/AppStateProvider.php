<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\State;

use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use GraphQLByPoP\GraphQLRequest\StaticHelpers\GraphQLQueryPayloadRetriever;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
    private ?GraphQLQueryConvertorInterface $graphQLQueryConvertor = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    final public function setGraphQLQueryConvertor(GraphQLQueryConvertorInterface $graphQLQueryConvertor): void
    {
        $this->graphQLQueryConvertor = $graphQLQueryConvertor;
    }
    final protected function getGraphQLQueryConvertor(): GraphQLQueryConvertorInterface
    {
        return $this->graphQLQueryConvertor ??= $this->instanceManager->getInstance(GraphQLQueryConvertorInterface::class);
    }

    public function initialize(array &$state): void
    {
        $state['graphql-operation-name'] = null;
        $state['graphql-operation-type'] = null;
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
        $state['graphql-operation-name'] = $payload['operationName'] ?? null;
    }

    public function augment(array &$state): void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        $state['standard-graphql'] = true;

        // @todo Remove this code, to temporarily convert back from GraphQL to PoP query
        // ---------------------------------------------
        if ($state['query'] !== null) {
            list(
                $operationType,
                $fieldQuery
            ) = $this->getGraphQLQueryConvertor()->convertFromGraphQLToFieldQuery(
                $state['query'],
                $state['variables'],
                $state['graphql-operation-name'],
            );
            $state['query'] = $fieldQuery;

            // Set the operation type and, based on it, if mutations are supported
            $state['graphql-operation-type'] = $operationType;
            $state['are-mutations-enabled'] = $operationType === OperationTypes::MUTATION;

            // If there was an error when parsing the query, the operationType will be null,
            // then there's no need to execute the query
            if ($operationType === null) {
                $state['does-api-query-have-errors'] = true;
            }
        }

        // Do not include the fieldArgs and directives when outputting the field
        $state['only-fieldname-as-outputkey'] = true;
        // ---------------------------------------------
    }
}

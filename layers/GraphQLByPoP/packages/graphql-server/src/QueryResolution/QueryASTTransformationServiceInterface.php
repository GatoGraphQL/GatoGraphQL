<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

use PoPAPI\API\QueryResolution\QueryASTTransformationServiceInterface as UpstreamQueryASTTransformationServiceInterface;

interface QueryASTTransformationServiceInterface extends UpstreamQueryASTTransformationServiceInterface
{
    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    public function convertOperationsToContainGraphQLSuperRootFields(array $operations): array;
}

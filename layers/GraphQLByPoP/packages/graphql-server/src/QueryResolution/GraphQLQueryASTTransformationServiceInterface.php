<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

use PoPAPI\API\QueryResolution\QueryASTTransformationServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

interface GraphQLQueryASTTransformationServiceInterface extends QueryASTTransformationServiceInterface
{
    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * Object caching (via alias) is mandatory:
     * Always return the same object for the same Operation!
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     */
    public function getGraphQLSuperRootOperationField(
        Document $document,
        OperationInterface $operation
    ): FieldInterface;
}

<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

use PoPAPI\API\QueryResolution\QueryASTTransformationServiceInterface as UpstreamQueryASTTransformationServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface QueryASTTransformationServiceInterface extends UpstreamQueryASTTransformationServiceInterface
{
    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     */
    public function convertOperationsToSuperRootFieldsInAST(Document $document): Document;
}

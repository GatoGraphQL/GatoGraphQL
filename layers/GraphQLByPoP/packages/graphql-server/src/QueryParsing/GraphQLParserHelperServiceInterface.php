<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryParsing;

use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface as UpstreamGraphQLParserHelperServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface GraphQLParserHelperServiceInterface extends UpstreamGraphQLParserHelperServiceInterface
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

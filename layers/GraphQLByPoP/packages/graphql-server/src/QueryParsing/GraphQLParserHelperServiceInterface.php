<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryParsing;

use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface as UpstreamGraphQLParserHelperServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface GraphQLParserHelperServiceInterface extends UpstreamGraphQLParserHelperServiceInterface
{
    public function convertOperationsToSuperRootFieldsInAST(Document $document): Document;
}

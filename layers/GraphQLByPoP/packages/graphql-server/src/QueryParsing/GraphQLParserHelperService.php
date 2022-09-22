<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryParsing;

use PoPAPI\API\QueryParsing\GraphQLParserHelperService as UpstreamGraphQLParserHelperService;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;

class GraphQLParserHelperService extends UpstreamGraphQLParserHelperService implements GraphQLParserHelperServiceInterface
{
    protected function parseQuery(ParserInterface $parser, string $query): Document
    {
        return $this->convertOperationsToSuperRootFieldsInAST(
            parent::parseQuery($parser, $query)
        );
    }

    public function convertOperationsToSuperRootFieldsInAST(Document $document): Document
    {
        return $document;
    }
}

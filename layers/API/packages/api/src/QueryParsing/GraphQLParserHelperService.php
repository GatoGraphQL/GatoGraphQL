<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryParsing;

use PoPAPI\API\ObjectModels\GraphQLQueryParsingPayload;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\Exception\Parser\ASTNodeParserException;
use PoP\GraphQLParser\Exception\Parser\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\Services\BasicServiceTrait;

class GraphQLParserHelperService implements GraphQLParserHelperServiceInterface
{
    use BasicServiceTrait;

    /**
     * @throws SyntaxErrorException
     * @throws FeatureNotSupportedException
     * @throws ASTNodeParserException
     * @param array<string,mixed> $variableValues
     */
    public function parseGraphQLQuery(
        string $query,
        array $variableValues,
        ?string $operationName,
    ): GraphQLQueryParsingPayload {
        $parser = $this->createParser();
        $document = $this->parseQuery($parser, $query);
        $executableDocument = new ExecutableDocument(
            $document,
            new Context($operationName, $variableValues)
        );
        return new GraphQLQueryParsingPayload(
            $executableDocument,
            $parser->getObjectResolvedFieldValueReferencedFields(),
        );
    }

    protected function createParser(): ParserInterface
    {
        return new Parser();
    }

    protected function parseQuery(ParserInterface $parser, string $query): Document
    {
        return $parser->parse($query);
    }
}

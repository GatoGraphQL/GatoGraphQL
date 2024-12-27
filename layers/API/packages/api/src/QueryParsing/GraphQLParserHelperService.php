<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryParsing;

use PoPAPI\API\ObjectModels\GraphQLQueryParsingPayload;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\Exception\Parser\LogicErrorParserException;
use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\Services\AbstractBasicService;

class GraphQLParserHelperService extends AbstractBasicService implements GraphQLParserHelperServiceInterface
{
    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws LogicErrorParserException
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

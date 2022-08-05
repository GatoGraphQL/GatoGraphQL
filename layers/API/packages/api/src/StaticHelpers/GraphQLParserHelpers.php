<?php

declare(strict_types=1);

namespace PoPAPI\API\StaticHelpers;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;

class GraphQLParserHelpers
{
    protected static function createParser(): ParserInterface
    {
        return new Parser();
    }

    /**
     * @throws SyntaxErrorException
     * @throws InvalidRequestException
     */
    public static function parseGraphQLQuery(
        string $query,
        array $variableValues,
        ?string $operationName,
    ): ExecutableDocument {
        $parser = static::createParser();
        $document = $parser->parse($query);
        return new ExecutableDocument(
            $document,
            new Context($operationName, $variableValues)
        );
    }
}

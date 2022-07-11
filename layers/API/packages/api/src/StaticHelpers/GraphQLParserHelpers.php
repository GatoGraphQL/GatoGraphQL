<?php

declare(strict_types=1);

namespace PoPAPI\API\StaticHelpers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;

class GraphQLParserHelpers
{
    protected static function getParser(): ParserInterface
    {
        return App::getContainer()->get(ParserInterface::class);
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
        $document = static::getParser()->parse($query);
        /** @var ExecutableDocument */
        $executableDocument = (
            new ExecutableDocument(
                $document,
                new Context($operationName, $variableValues)
            )
        )->validateAndInitialize();
        return $executableDocument;
    }
}

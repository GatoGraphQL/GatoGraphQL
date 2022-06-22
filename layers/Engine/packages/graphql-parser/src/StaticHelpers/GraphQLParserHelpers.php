<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\StaticHelpers;

use PoP\ComponentModel\App;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
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
        $document = static::getParser()->parse($query)->setAncestorsInAST();
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

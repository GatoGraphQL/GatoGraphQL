<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryParsing;

use PoP\GraphQLParser\Exception\Parser\ASTNodeParserException;
use PoP\GraphQLParser\Exception\Parser\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoPAPI\API\ObjectModels\GraphQLQueryParsingPayload;

interface GraphQLParserHelperServiceInterface
{
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
    ): GraphQLQueryParsingPayload;
}

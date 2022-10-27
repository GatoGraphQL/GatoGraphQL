<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\Exception\Parser\UnsupportedSyntaxErrorParserException;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface ParserInterface
{
    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws UnsupportedSyntaxErrorParserException
     */
    public function parse(string $source): Document;
}

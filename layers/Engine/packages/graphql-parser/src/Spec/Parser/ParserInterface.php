<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\Parser\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface ParserInterface
{
    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     */
    public function parse(string $source): Document;
}

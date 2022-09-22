<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\Parser\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface ParserInterface
{
    /**
     * @throws SyntaxErrorException
     * @throws FeatureNotSupportedException
     */
    public function parse(string $source): Document;
}

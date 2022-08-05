<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution;

use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentTest as UpstreamExecutableDocumentTest;

class ExecutableDocumentTest extends UpstreamExecutableDocumentTest
{
    private ?ParserInterface $parser = null;

    protected function getParser(): ParserInterface
    {
        return $this->parser ??= new Parser();
    }
}

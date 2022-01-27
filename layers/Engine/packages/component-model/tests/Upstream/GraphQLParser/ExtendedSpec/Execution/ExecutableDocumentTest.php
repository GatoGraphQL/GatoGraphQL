<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentTest as UpstreamExecutableDocumentTest;

class ExecutableDocumentTest extends UpstreamExecutableDocumentTest
{
    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }
}

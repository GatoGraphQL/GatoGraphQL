<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentTest as UpstreamExecutableDocumentTest;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

class ExecutableDocumentTest extends UpstreamExecutableDocumentTest
{
    protected function createExecutableDocument(
      Document $document,
      Context $context,
    ): ExecutableDocumentInterface {
        return new ExecutableDocument($document, $context);
    }
}

<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;

class ExecutableDocument implements ExecutableDocumentInterface
{
    public function __construct(
        private Document $document,
        private array $variableValues = [],
        private ?string $operationName = null,
    ) {
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void
    {

    }
}

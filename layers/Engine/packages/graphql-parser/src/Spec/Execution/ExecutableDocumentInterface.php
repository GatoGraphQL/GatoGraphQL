<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

interface ExecutableDocumentInterface
{
    /**
     * Calculate the executable operations, and
     * integrate the variableValues for them.
     *
     * If any validation fails, throw an exception.
     *
     * @throws InvalidRequestException
     *
     * @see https://spec.graphql.org/draft/#sec-Validation
     */
    public function validateAndInitialize(): self;

    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    public function getRequestedOperations(): array;

    public function getDocument(): Document;
    public function getContext(): Context;
}

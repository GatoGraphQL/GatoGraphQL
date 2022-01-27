<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;

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

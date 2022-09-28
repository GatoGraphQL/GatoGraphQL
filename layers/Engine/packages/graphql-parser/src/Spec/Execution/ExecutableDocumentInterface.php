<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
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
    public function validateAndInitialize(): void;
    /**
     * The actual requested operation. Even though with Multiple Query Execution
     * the document can contain multiple operations, there is only one that
     * can be requested via ?operationName=...
     *
     * @throws InvalidRequestException
     */
    public function getRequestedOperation(): ?OperationInterface;

    public function getDocument(): Document;
    public function getContext(): Context;
}

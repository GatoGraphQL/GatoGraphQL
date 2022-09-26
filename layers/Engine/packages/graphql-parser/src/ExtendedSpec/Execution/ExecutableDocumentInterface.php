<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentInterface as UpstreamExecutableDocumentInterface;

interface ExecutableDocumentInterface extends UpstreamExecutableDocumentInterface
{
    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    public function getMultipleOperationsToExecute(): array;
}

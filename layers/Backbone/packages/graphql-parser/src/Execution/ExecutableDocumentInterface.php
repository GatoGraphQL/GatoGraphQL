<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
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
    public function validateAndMerge(): void;

    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    public function getExecutableOperations(): array;

    /**
     * @return array<string,array<string, mixed>.
     */
    public function getOperationVariableValues(): array;
    public function getOperationVariableValue(OperationInterface $operation, string $variableName): mixed;
}

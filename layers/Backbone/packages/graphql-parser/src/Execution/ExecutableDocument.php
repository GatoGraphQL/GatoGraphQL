<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use GraphQLByPoP\GraphQLQuery\Schema\ClientSymbols;
use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class ExecutableDocument implements ExecutableDocumentInterface
{
    public function __construct(
        private Document $document,
        private array $variableValues = [],
        private string $operationName = '',
    ) {
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void
    {
        $operationsToExecute = [];
        // Executing `__ALL`?
        $executeAllOperations = $this->operationName === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME;
        foreach ($this->document->getOperations() as $operation) {
            if (!($executeAllOperations || $operation->getName() === $this->operationName)) {
                continue;
            }
            $operationsToExecute[] = $operation;
        }

        if ($operationsToExecute === []) {
            throw new InvalidRequestException('saranga', new Location(0, 0));
        }

        foreach ($operationsToExecute as $operation) {
            $this->validateOperation($operation);
        }

        foreach ($operationsToExecute as $operation) {
            $this->mergeOperation($operation);
        }
    }

    protected function validateOperation(OperationInterface $operation): void
    {

    }

    protected function mergeOperation(OperationInterface $operation): void
    {

    }
}

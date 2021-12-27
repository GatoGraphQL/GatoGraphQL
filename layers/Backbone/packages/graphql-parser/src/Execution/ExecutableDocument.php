<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class ExecutableDocument implements ExecutableDocumentInterface
{
    private Context $context;
    private ?array $executableOperations = null;

    public function __construct(
        private Document $document,
        /** @var array<string, mixed> */
        array $variableValues = [],
        ?string $operationName = null,
    ) {
        $this->context = new Context($operationName, $variableValues);
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void
    {
        $this->executableOperations = null;

        $this->assertFragmentReferencesAreValid();
        $this->assertFragmentsAreUsed();
        $this->assertAllVariablesExist();
        $this->assertAllVariablesAreUsed();
        $this->assertAllVariablesHaveValue();

        // Obtain the operations that must be executed
        $this->executableOperations = $this->assertAndGetRequestedOperations(
            $this->context->getOperationName()
        );

        // Inject the variable values into the objects
        foreach ($this->executableOperations as $operation) {
            $this->initializeOperationVariableValues($operation);
        }
    }

    /**
     * Even though the GraphQL spec allows to execute only 1 Operation,
     * retrieve a list of Operations, allowing to override it
     * for the "multiple query execution" feature.
     *
     * @return OperationInterface[]
     * @throws InvalidRequestException
     *
     * @see https://spec.graphql.org/draft/#sec-Executing-Requests
     */
    protected function assertAndGetRequestedOperations(): array
    {
        $operationCount = count($this->document->getOperations());
        if ($operationCount === 0) {
            throw new InvalidRequestException(
                $this->getNoOperationsProvidedErrorMessage(),
                new Location(1, 1)
            );
        }

        if ($this->context->getOperationName() === '') {
            if ($operationCount > 1) {
                throw new InvalidRequestException(
                    $this->getNoOperationNameProvidedErrorMessage(),
                    new Location(1, 1)
                );
            }
            // There is exactly 1 operation
            return $this->document->getOperations();
        }

        $selectedOperations = $this->getSelectedOperationsToExecute();
        if ($selectedOperations === []) {
            throw new InvalidRequestException(
                $this->getNoOperationMatchesNameErrorMessage($this->context->getOperationName()),
                new Location(1, 1)
            );
        }

        // There can be many operations
        return $selectedOperations;
    }

    /**
     * @return OperationInterface[]
     */
    protected function getSelectedOperationsToExecute(): array
    {
        $operationName = $this->context->getOperationName();
        return array_values(array_filter(
            $this->document->getOperations(),
            fn (OperationInterface $operation) => $operation->getName() === $operationName
        ));
    }

    protected function getNoOperationMatchesNameErrorMessage(string $operationName): string
    {
        return \sprintf('Operation with name \'%s\' does not exist', $operationName);
    }

    protected function getNoOperationsProvidedErrorMessage(): string
    {
        return \sprintf('No operations were provided in the query');
    }

    protected function getNoOperationNameProvidedErrorMessage(): string
    {
        return \sprintf('The operation name must be provided');
    }

    /**
     * Validate that all referenced variable are provided a value,
     * or they have a default value. Otherwise, throw an exception.
     *
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesHaveValue(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($operation->getVariableReferences() as $variableReference) {
                /** @var Variable */
                $variable = $variableReference->getVariable();
                if (array_key_exists($variable->getName(), $this->context->getVariableValues())
                    || $variable->hasDefaultValue()
                ) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getVariableHasntBeenSubmittedErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
            }
        }
    }

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return \sprintf('Variable \'%s\' hasn\'t been declared', $variableName);
    }

    protected function getVariableHasntBeenSubmittedErrorMessage(string $variableName): string
    {
        return \sprintf('Variable \'%s\' hasn\'t been submitted', $variableName);
    }

    protected function initializeOperationVariableValues(OperationInterface $operation): void
    {
        foreach ($operation->getVariableReferences() as $variableReference) {
            $variableReference->setContext($this->context);
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentsAreUsed(): void
    {
        $referencedFragmentNames = [];
        foreach ($this->document->getOperations() as $operation) {
            foreach ($operation->getFragmentReferences() as $fragmentReference) {
                $referencedFragmentNames[] = $fragmentReference->getName();
            }
        }
        $referencedFragmentNames = array_values(array_unique($referencedFragmentNames));

        foreach ($this->document->getFragments() as $fragment) {
            if (in_array($fragment->getName(), $referencedFragmentNames)) {
                continue;
            }
            throw new InvalidRequestException(
                $this->getFragmentNotUsedErrorMessage($fragment->getName()),
                $fragment->getLocation()
            );
        }
    }

    protected function getFragmentNotUsedErrorMessage(string $fragmentName): string
    {
        return sprintf('Fragment \'%s\' not used', $fragmentName);
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentReferencesAreValid(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($operation->getFragmentReferences() as $fragmentReference) {
                if ($this->document->getFragment($fragmentReference->getName()) !== null) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getFragmentNotDefinedInQueryErrorMessage($fragmentReference->getName()),
                    $fragmentReference->getLocation()
                );
            }
        }
    }

    protected function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string
    {
        return sprintf('Fragment \'%s\' not defined in query', $fragmentName);
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesExist(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($operation->getVariableReferences() as $variableReference) {
                if ($variableReference->getVariable() !== null) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getVariableDoesNotExistErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
            }
        }
    }

    protected function getVariableDoesNotExistErrorMessage(string $variableName): string
    {
        return sprintf('Variable \'%s\' does not exist', $variableName);
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesAreUsed(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            $referencedVariableNames = [];
            foreach ($operation->getVariableReferences() as $variableReference) {
                $referencedVariableNames[] = $variableReference->getName();
            }
            $referencedVariableNames = array_values(array_unique($referencedVariableNames));

            foreach ($operation->getVariables() as $variable) {
                if (in_array($variable->getName(), $referencedVariableNames)) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getVariableNotUsedErrorMessage($variable->getName()),
                    $variable->getLocation()
                );
            }
        }
    }

    protected function getVariableNotUsedErrorMessage(string $variableName): string
    {
        return sprintf('Variable \'%s\' not used', $variableName);
    }

    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    public function getExecutableOperations(): array
    {
        if ($this->executableOperations === null) {
            throw new InvalidRequestException(
                $this->getExecuteValidationErrorMessage(__FUNCTION__),
                new Location(1, 1)
            );
        }
        return $this->executableOperations;
    }

    protected function getExecuteValidationErrorMessage(string $methodName): string
    {
        return sprintf(
            'Before executing `%s`, must call `%s`',
            $methodName,
            'validateAndMerge'
        );
    }
}

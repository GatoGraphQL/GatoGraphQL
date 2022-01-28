<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Facades\Error\GraphQLErrorMessageProviderFacade;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Services\StandaloneServiceTrait;

class Document implements DocumentInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider = null;

    final public function setGraphQLErrorMessageProvider(GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider): void
    {
        $this->graphQLErrorMessageProvider = $graphQLErrorMessageProvider;
    }
    final protected function getGraphQLErrorMessageProvider(): GraphQLErrorMessageProviderInterface
    {
        return $this->graphQLErrorMessageProvider ??= GraphQLErrorMessageProviderFacade::getInstance();
    }

    public function __construct(
        /** @var OperationInterface[] */
        private array $operations,
        /** @var Fragment[] */
        private array $fragments = [],
    ) {
    }

    /**
     * @return OperationInterface[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @return Fragment[]
     */
    public function getFragments(): array
    {
        return $this->fragments;
    }

    public function getFragment(string $name): ?Fragment
    {
        foreach ($this->fragments as $fragment) {
            if ($fragment->getName() === $name) {
                return $fragment;
            }
        }

        return null;
    }

    /**
     * @throws InvalidRequestException
     */
    public function validate(): void
    {
        $this->assertOperationsDefined();
        $this->assertOperationNamesUnique();
        $this->assertNonEmptyOperationName();
        $this->assertFragmentReferencesAreValid();
        $this->assertFragmentsAreUsed();
        $this->assertVariableNamesUnique();
        $this->assertAllVariablesExist();
        $this->assertAllVariablesAreUsed();
        $this->assertArgumentsUnique();
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertOperationsDefined(): void
    {
        if ($this->getOperations() === []) {
            throw new InvalidRequestException(
                $this->getGraphQLErrorMessageProvider()->getNoOperationsDefinedInQueryErrorMessage(),
                $this->getNonSpecificLocation()
            );
        }
    }

    protected function getNonSpecificLocation(): Location
    {
        return new Location(1, 1);
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertOperationNamesUnique(): void
    {
        $operationNames = [];
        foreach ($this->getOperations() as $operation) {
            $operationName = $operation->getName();
            if (in_array($operationName, $operationNames)) {
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getDuplicateOperationNameErrorMessage($operationName),
                    $this->getNonSpecificLocation()
                );
            }
            $operationNames[] = $operationName;
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertNonEmptyOperationName(): void
    {
        if (count($this->getOperations()) === 1) {
            return;
        }
        foreach ($this->getOperations() as $operation) {
            if (empty($operation->getName())) {
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getEmptyOperationNameErrorMessage(),
                    $this->getNonSpecificLocation()
                );
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentReferencesAreValid(): void
    {
        foreach ($this->getOperations() as $operation) {
            foreach ($this->getFragmentReferencesInOperation($operation) as $fragmentReference) {
                if ($this->getFragment($fragmentReference->getName()) !== null) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getFragmentNotDefinedInQueryErrorMessage($fragmentReference->getName()),
                    $fragmentReference->getLocation()
                );
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentsAreUsed(): void
    {
        $referencedFragmentNames = [];

        // Collect fragment references in all operations
        foreach ($this->getOperations() as $operation) {
            foreach ($this->getFragmentReferencesInOperation($operation) as $fragmentReference) {
                $referencedFragmentNames[] = $fragmentReference->getName();
            }
        }

        // Collect fragment references in all fragments
        foreach ($this->getFragments() as $fragment) {
            foreach ($fragment->getFieldsOrFragmentBonds() as $fieldsOrFragmentBond) {
                if (!($fieldsOrFragmentBond instanceof FragmentReference)) {
                    continue;
                }
                /** @var FragmentReference */
                $fragmentReference = $fieldsOrFragmentBond;
                $referencedFragmentNames[] = $fragmentReference->getName();
            }
        }

        $referencedFragmentNames = array_values(array_unique($referencedFragmentNames));

        foreach ($this->getFragments() as $fragment) {
            if (in_array($fragment->getName(), $referencedFragmentNames)) {
                continue;
            }
            throw new InvalidRequestException(
                $this->getGraphQLErrorMessageProvider()->getFragmentNotUsedErrorMessage($fragment->getName()),
                $fragment->getLocation()
            );
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertVariableNamesUnique(): void
    {
        foreach ($this->getOperations() as $operation) {
            $variableNames = [];
            foreach ($operation->getVariables() as $variable) {
                $variableName = $variable->getName();
                if (in_array($variableName, $variableNames)) {
                    throw new InvalidRequestException(
                        $this->getGraphQLErrorMessageProvider()->getDuplicateVariableNameErrorMessage($variableName),
                        $this->getNonSpecificLocation()
                    );
                }
                $variableNames[] = $variableName;
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesExist(): void
    {
        foreach ($this->getOperations() as $operation) {
            foreach ($operation->getVariableReferences($this->getFragments()) as $variableReference) {
                if ($variableReference->getVariable() !== null) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getVariableNotDefinedInOperationErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesAreUsed(): void
    {
        foreach ($this->getOperations() as $operation) {
            $referencedVariableNames = [];
            foreach ($operation->getVariableReferences($this->getFragments()) as $variableReference) {
                $referencedVariableNames[] = $variableReference->getName();
            }
            $referencedVariableNames = array_values(array_unique($referencedVariableNames));

            foreach ($operation->getVariables() as $variable) {
                if (in_array($variable->getName(), $referencedVariableNames)) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getVariableNotUsedErrorMessage($variable->getName()),
                    $variable->getLocation()
                );
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUnique(): void
    {
        foreach ($this->getOperations() as $operation) {
            $this->assertArgumentsUniqueInOperation($operation);
        }
        foreach ($this->getFragments() as $fragment) {
            $this->assertArgumentsUniqueInFragment($fragment);
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUniqueInOperation(OperationInterface $operation): void
    {
        $this->assertArgumentsUniqueInFieldsOrInlineFragments($operation->getFieldsOrFragmentBonds());
        $this->assertArgumentsUniqueInDirectives($operation->getDirectives());
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUniqueInFragment(Fragment $fragment): void
    {
        $this->assertArgumentsUniqueInFieldsOrInlineFragments($fragment->getFieldsOrFragmentBonds());
        $this->assertArgumentsUniqueInDirectives($fragment->getDirectives());
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUniqueInFieldsOrInlineFragments(array $fieldsOrFragmentBonds): void
    {
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $this->assertArgumentsUniqueInFieldsOrInlineFragments($inlineFragment->getFieldsOrFragmentBonds());
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $this->assertArgumentNamesUnique($field->getArguments());
            $this->assertArgumentsUniqueInDirectives($field->getDirectives());
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $this->assertArgumentsUniqueInFieldsOrInlineFragments($relationalField->getFieldsOrFragmentBonds());
            }
        }
    }

    /**
     * @param Directive[] $directives
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUniqueInDirectives(array $directives): void
    {
        foreach ($directives as $directive) {
            $this->assertArgumentNamesUnique($directive->getArguments());
        }
    }

    /**
     * @param Argument[] $arguments
     * @throws InvalidRequestException
     */
    protected function assertArgumentNamesUnique(array $arguments): void
    {
        $argumentNames = [];
        foreach ($arguments as $argument) {
            $argumentName = $argument->getName();
            if (in_array($argumentName, $argumentNames)) {
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getDuplicateArgumentErrorMessage($argumentName),
                    $argument->getLocation()
                );
            }
            $argumentNames[] = $argumentName;
        }
    }

    /**
     * Gather all the FragmentReference within the Operation.
     *
     * @return FragmentReference[]
     */
    public function getFragmentReferencesInOperation(OperationInterface $operation): array
    {
        $referencedFragmentNames = [];
        return $this->getFragmentReferencesInFieldsOrFragmentBonds($operation->getFieldsOrFragmentBonds(), $referencedFragmentNames);
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param string[] $referencedFragmentNames To stop circular fragments
     * @return FragmentReference[]
     */
    protected function getFragmentReferencesInFieldsOrFragmentBonds(array $fieldsOrFragmentBonds, array &$referencedFragmentNames): array
    {
        $fragmentReferences = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof LeafField) {
                continue;
            }
            if (
                $fieldOrFragmentBond instanceof InlineFragment
                || $fieldOrFragmentBond instanceof RelationalField
            ) {
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->getFragmentReferencesInFieldsOrFragmentBonds($fieldOrFragmentBond->getFieldsOrFragmentBonds(), $referencedFragmentNames)
                );
                continue;
            }
            /** @var FragmentReference */
            $fragmentReference = $fieldOrFragmentBond;
            /**
             * Avoid circular references
             */
            if (in_array($fragmentReference->getName(), $referencedFragmentNames)) {
                continue;
            }
            $fragmentReferences[] = $fragmentReference;
            $referencedFragmentNames[] = $fragmentReference->getName();
            $fragment = $this->getFragment($fragmentReference->getName());
            if ($fragment === null) {
                continue;
            }
            $fragmentReferences = array_merge(
                $fragmentReferences,
                $this->getFragmentReferencesInFieldsOrFragmentBonds($fragment->getFieldsOrFragmentBonds(), $referencedFragmentNames)
            );
        }
        return $fragmentReferences;
    }
}

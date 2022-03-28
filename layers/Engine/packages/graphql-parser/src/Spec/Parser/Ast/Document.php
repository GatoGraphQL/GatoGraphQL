<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Services\StandaloneServiceTrait;

class Document implements DocumentInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        /** @var OperationInterface[] */
        protected array $operations,
        /** @var Fragment[] */
        protected array $fragments = [],
    ) {
    }

    final public function __toString(): string
    {
        return $this->asDocumentString();
    }

    public function asDocumentString(): string
    {
        $strOperationAndFragments = [];
        foreach ($this->operations as $operation) {
            $strOperationAndFragments[] = $operation->asQueryString();
        }
        foreach ($this->fragments as $fragment) {
            $strOperationAndFragments[] = $fragment->asQueryString();
        }
        return implode('\n', $strOperationAndFragments);
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
        $this->assertQueryNotEmpty();
        $this->assertOperationsDefined();
        $this->assertOperationNamesUnique();
        $this->assertNonEmptyOperationName();
        $this->assertFragmentReferencesAreValid();
        $this->assertFragmentNamesUnique();
        $this->assertNoCyclicalFragments();
        $this->assertFragmentsAreUsed();
        $this->assertVariableNamesUnique();
        $this->assertAllVariablesExist();
        $this->assertAllVariablesAreUsed();
        $this->assertArgumentsUnique();
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertQueryNotEmpty(): void
    {
        if ($this->getOperations() === [] && $this->getFragments() === []) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_6_1_C,
                ),
                LocationHelper::getNonSpecificLocation()
            );
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertOperationsDefined(): void
    {
        if ($this->getOperations() === []) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_6_1_D,
                ),
                LocationHelper::getNonSpecificLocation()
            );
        }
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
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_2_1_1,
                        [
                            $operationName,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation()
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
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_2_2_1,
                    ),
                    LocationHelper::getNonSpecificLocation()
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
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_5_2_1,
                        [
                            $fragmentReference->getName(),
                        ]
                    ),
                    $fragmentReference->getLocation()
                );
            }
        }
    }

    /**
     * Gather all the FragmentReference within the Operation.
     *
     * @return FragmentReference[]
     */
    protected function getFragmentReferencesInOperation(OperationInterface $operation): array
    {
        $referencedFragmentNames = [];
        return $this->getFragmentReferencesInFieldsOrFragmentBonds($operation->getFieldsOrFragmentBonds(), $referencedFragmentNames);
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param string[] $referencedFragmentNames To stop cyclical fragments
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
             * Avoid cyclical references
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

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentNamesUnique(): void
    {
        $fragmentNames = [];
        foreach ($this->getFragments() as $fragment) {
            $fragmentName = $fragment->getName();
            if (in_array($fragmentName, $fragmentNames)) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_1,
                        [
                            $fragmentName,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation()
                );
            }
            $fragmentNames[] = $fragmentName;
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertNoCyclicalFragments(): void
    {
        foreach ($this->getFragments() as $fragment) {
            $fragmentReferences = $this->getFragmentReferencesInFragment($fragment);
            foreach ($fragmentReferences as $fragmentReference) {
                if ($fragmentReference->getName() !== $fragment->getName()) {
                    continue;
                }
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_5_2_2,
                        [
                            $fragmentReference->getName(),
                        ]
                    ),
                    $fragmentReference->getLocation()
                );
            }
        }
    }

    /**
     * Gather all the FragmentReference within the Operation.
     *
     * @return FragmentReference[]
     */
    protected function getFragmentReferencesInFragment(Fragment $fragment): array
    {
        $referencedFragmentNames = [];
        return $this->getFragmentReferencesInFieldsOrFragmentBonds($fragment->getFieldsOrFragmentBonds(), $referencedFragmentNames);
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
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_4,
                    [
                        $fragment->getName(),
                    ]
                ),
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
                        new FeedbackItemResolution(
                            GraphQLSpecErrorFeedbackItemProvider::class,
                            GraphQLSpecErrorFeedbackItemProvider::E_5_8_1,
                            [
                                $variableName,
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation()
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
            foreach ($this->getVariableReferencesInOperation($operation) as $variableReference) {
                if ($this->isVariableDefined($variableReference)) {
                    continue;
                }
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_8_3,
                        [
                            $variableReference->getName(),
                        ]
                    ),
                    $variableReference->getLocation()
                );
            }
        }
    }

    /**
     * Can override for the Extended Spec
     */
    protected function isVariableDefined(
        VariableReference $variableReference,
    ): bool {
        return $variableReference->getVariable() !== null;
    }

    /**
     * Gather all the VariableReference within the Operation.
     *
     * @return VariableReference[]
     */
    public function getVariableReferencesInOperation(OperationInterface $operation): array
    {
        return array_merge(
            $this->getVariableReferencesInFieldsOrFragments($operation->getFieldsOrFragmentBonds()),
            $this->getVariableReferencesInDirectives($operation->getDirectives())
        );
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @return VariableReference[]
     */
    protected function getVariableReferencesInFieldsOrFragments(array $fieldsOrFragmentBonds): array
    {
        $variableReferences = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragmentReference->getName());
                if ($fragment === null) {
                    continue;
                }
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($fragment->getFieldsOrFragmentBonds())
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($inlineFragment->getFieldsOrFragmentBonds())
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInArguments($field->getArguments()),
                $this->getVariableReferencesInDirectives($field->getDirectives())
            );
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($relationalField->getFieldsOrFragmentBonds())
                );
                continue;
            }
        }
        return $variableReferences;
    }

    /**
     * @param Directive[] $directives
     * @return VariableReference[]
     */
    protected function getVariableReferencesInDirectives(array $directives): array
    {
        $variableReferences = [];
        foreach ($directives as $directive) {
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInArguments($directive->getArguments())
            );
        }
        return $variableReferences;
    }

    /**
     * @param Argument[] $arguments
     * @return VariableReference[]
     */
    protected function getVariableReferencesInArguments(array $arguments): array
    {
        $variableReferences = [];
        foreach ($arguments as $argument) {
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInArgumentValue($argument->getValue())
            );
        }
        return $variableReferences;
    }

    /**
     * @return VariableReference[]
     */
    protected function getVariableReferencesInArgumentValue(WithValueInterface $argumentValue): array
    {
        if ($argumentValue instanceof VariableReference) {
            return [$argumentValue];
        }
        if (!($argumentValue instanceof InputObject || $argumentValue instanceof InputList)) {
            return [];
        }
        // Get references within InputObjects and Lists
        $variableReferences = [];
        $listValues = (array)$argumentValue->getAstValue();
        foreach ($listValues as $listValue) {
            if (!($listValue instanceof VariableReference || $listValue instanceof WithValueInterface)) {
                continue;
            }
            if ($listValue instanceof VariableReference) {
                $variableReferences[] = $listValue;
                continue;
            }
            /** @var WithValueInterface $listValue */
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInArgumentValue($listValue)
            );
        }
        return $variableReferences;
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesAreUsed(): void
    {
        foreach ($this->getOperations() as $operation) {
            $referencedVariableNames = [];
            foreach ($this->getVariableReferencesInOperation($operation) as $variableReference) {
                $referencedVariableNames[] = $variableReference->getName();
            }
            $referencedVariableNames = array_values(array_unique($referencedVariableNames));

            foreach ($operation->getVariables() as $variable) {
                if (in_array($variable->getName(), $referencedVariableNames)) {
                    continue;
                }
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_8_4,
                        [
                            $variable->getName(),
                        ]
                    ),
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
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_2,
                        [
                            $argumentName,
                        ]
                    ),
                    $argument->getLocation()
                );
            }
            $argumentNames[] = $argumentName;
        }
    }
}

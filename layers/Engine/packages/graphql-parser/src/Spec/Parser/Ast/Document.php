<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

class Document implements DocumentInterface
{
    use StandaloneServiceTrait;

    /**
     * Keep a reference to the dictionary of all ancestors
     * for each AST node.
     *
     * @var SplObjectStorage<AstInterface,AstInterface>|null
     */
    protected ?SplObjectStorage $astNodeAncestors = null;

    public function __construct(
        /** @var OperationInterface[] */
        protected readonly array $operations,
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
        return implode(PHP_EOL . PHP_EOL, $strOperationAndFragments);
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
                // Create a new Object as to pass the Location
                new QueryOperation('', [], [], [], LocationHelper::getNonSpecificLocation())
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
                // Create a new Object as to pass the Location
                new QueryOperation('', [], [], [], LocationHelper::getNonSpecificLocation())
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
                    $operation
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
                    $operation
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
                    $fragmentReference
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
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
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
                    $fragment
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
                    $fragmentReference
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
                $fragment
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
                        $variable
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
                    $variableReference
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
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
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
                $this->getVariableReferencesInArgumentValue($argument->getValueAST())
            );
        }
        return $variableReferences;
    }

    /**
     * @param WithValueInterface|array<WithValueInterface|array<mixed>> $argumentValue
     * @return VariableReference[]
     */
    protected function getVariableReferencesInArgumentValue(WithValueInterface|array $argumentValue): array
    {
        if ($argumentValue instanceof VariableReference) {
            return [$argumentValue];
        }
        if (!(is_array($argumentValue) || $argumentValue instanceof InputObject || $argumentValue instanceof InputList)) {
            return [];
        }
        // Get references within InputObjects and Lists
        $variableReferences = [];
        /**
         * Handle array of arrays. Eg:
         *
         * ```
         * query UpperCaseText($text: String!) {
         *   echo(value: [[$text]])
         * }
         * ```
         */
        if (is_array($argumentValue)) {
            foreach ($argumentValue as $listValue) {
                if (!(is_array($listValue) || $listValue instanceof VariableReference || $listValue instanceof WithValueInterface)) {
                    continue;
                }
                /** @var WithValueInterface|array $listValue */
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInArgumentValue($listValue)
                );
            }
            return $variableReferences;
        }
        /** @var WithAstValueInterface $argumentValue */
        $listValues = (array)$argumentValue->getAstValue();
        foreach ($listValues as $listValue) {
            if (!(is_array($listValue) || $listValue instanceof VariableReference || $listValue instanceof WithValueInterface)) {
                continue;
            }
            if ($listValue instanceof VariableReference) {
                $variableReferences[] = $listValue;
                continue;
            }
            /** @var WithValueInterface|array $listValue */
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
                    $variable
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
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
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
                    $argument
                );
            }
            $argumentNames[] = $argumentName;
        }
    }

    /**
     * Create a dictionary mapping every element of the AST
     * to their parent. This is useful to report the full
     * path to an AST node in the query when displaying errors.
     *
     * @return SplObjectStorage<AstInterface,AstInterface>
     */
    public function getASTNodeAncestors(): SplObjectStorage
    {
        if ($this->astNodeAncestors === null) {
            /** @var SplObjectStorage<AstInterface,AstInterface> */
            $astNodeAncestors = new SplObjectStorage();
            $this->astNodeAncestors = $astNodeAncestors;
            foreach ($this->operations as $operation) {
                $this->setAncestorsUnderOperation($operation);
            }
            foreach ($this->fragments as $fragment) {
                $this->setAncestorsUnderFragment($fragment);
            }
        }
        return $this->astNodeAncestors;
    }

    protected function setAncestorsUnderOperation(OperationInterface $operation): void
    {
        foreach ($operation->getVariables() as $variable) {
            $this->astNodeAncestors[$variable] = $operation;
        }
        foreach ($operation->getDirectives() as $directive) {
            $this->astNodeAncestors[$directive] = $operation;
            $this->setAncestorsUnderDirective($directive);
        }
        foreach ($operation->getFieldsOrFragmentBonds() as $fieldOrFragmentBond) {
            $this->astNodeAncestors[$fieldOrFragmentBond] = $operation;
            $this->setAncestorsUnderFieldOrFragmentBond($fieldOrFragmentBond);
        }
    }

    protected function setAncestorsUnderDirective(Directive $directive): void
    {
        foreach ($directive->getArguments() as $argument) {
            $this->astNodeAncestors[$argument] = $directive;
            $this->setAncestorsUnderArgument($argument);
        }
    }

    protected function setAncestorsUnderArgument(Argument $argument): void
    {
        /** @var Enum|InputList|InputObject|Literal|VariableReference */
        $argumentValueAST = $argument->getValueAST();
        $this->astNodeAncestors[$argumentValueAST] = $argument;

        $this->setAncestorsUnderArgumentValueAst($argumentValueAST);
    }

    protected function setAncestorsUnderArgumentValueAst(Enum|InputList|InputObject|Literal|VariableReference $argumentValueAST): void
    {
        if ($argumentValueAST instanceof InputList) {
            /** @var InputList */
            $inputList = $argumentValueAST;
            $this->setAncestorsUnderInputList($inputList);
        }
        if ($argumentValueAST instanceof InputObject) {
            /** @var InputObject */
            $inputObject = $argumentValueAST;
            $this->setAncestorsUnderInputObject($inputObject);
        }
    }

    protected function setAncestorsUnderInputList(InputList $inputList): void
    {
        foreach ($inputList->getAstValue() as $astValue) {
            if (
                !(
                $astValue instanceof Enum
                || $astValue instanceof InputList
                || $astValue instanceof InputObject
                || $astValue instanceof Literal
                || $astValue instanceof VariableReference
                )
            ) {
                continue;
            }
            $this->astNodeAncestors[$astValue] = $inputList;
            $this->setAncestorsUnderArgumentValueAst($astValue);
        }
    }

    protected function setAncestorsUnderInputObject(InputObject $inputObject): void
    {
        foreach ((array) $inputObject->getAstValue() as $astValue) {
            if (
                !(
                $astValue instanceof Enum
                || $astValue instanceof InputList
                || $astValue instanceof InputObject
                || $astValue instanceof Literal
                || $astValue instanceof VariableReference
                )
            ) {
                continue;
            }
            $this->astNodeAncestors[$astValue] = $inputObject;
            $this->setAncestorsUnderArgumentValueAst($astValue);
        }
    }

    protected function setAncestorsUnderFieldOrFragmentBond(FieldInterface|FragmentBondInterface $fieldOrFragmentBond): void
    {
        if ($fieldOrFragmentBond instanceof LeafField) {
            /** @var LeafField */
            $leafField = $fieldOrFragmentBond;
            foreach ($leafField->getArguments() as $argument) {
                $this->astNodeAncestors[$argument] = $leafField;
                $this->setAncestorsUnderArgument($argument);
            }
            foreach ($leafField->getDirectives() as $directive) {
                $this->astNodeAncestors[$directive] = $leafField;
                $this->setAncestorsUnderDirective($directive);
            }
            return;
        }
        if ($fieldOrFragmentBond instanceof RelationalField) {
            /** @var RelationalField */
            $relationalField = $fieldOrFragmentBond;
            foreach ($relationalField->getArguments() as $argument) {
                $this->astNodeAncestors[$argument] = $relationalField;
                $this->setAncestorsUnderArgument($argument);
            }
            foreach ($relationalField->getDirectives() as $directive) {
                $this->astNodeAncestors[$directive] = $relationalField;
                $this->setAncestorsUnderDirective($directive);
            }
            foreach ($relationalField->getFieldsOrFragmentBonds() as $fieldOrFragmentBond) {
                $this->astNodeAncestors[$fieldOrFragmentBond] = $relationalField;
                $this->setAncestorsUnderFieldOrFragmentBond($fieldOrFragmentBond);
            }
            return;
        }
        if ($fieldOrFragmentBond instanceof InlineFragment) {
            /** @var InlineFragment */
            $inlineFragment = $fieldOrFragmentBond;
            foreach ($inlineFragment->getDirectives() as $directive) {
                $this->astNodeAncestors[$directive] = $inlineFragment;
                $this->setAncestorsUnderDirective($directive);
            }
            foreach ($inlineFragment->getFieldsOrFragmentBonds() as $fieldOrFragmentBond) {
                $this->astNodeAncestors[$fieldOrFragmentBond] = $inlineFragment;
                $this->setAncestorsUnderFieldOrFragmentBond($fieldOrFragmentBond);
            }
            return;
        }
        /** @var FragmentReference */
        $fragmentReference = $fieldOrFragmentBond;
        // Nothing to set here
    }

    protected function setAncestorsUnderFragment(Fragment $fragment): void
    {
        foreach ($fragment->getDirectives() as $directive) {
            $this->astNodeAncestors[$directive] = $fragment;
            $this->setAncestorsUnderDirective($directive);
        }
        foreach ($fragment->getFieldsOrFragmentBonds() as $fieldOrFragmentBond) {
            $this->astNodeAncestors[$fieldOrFragmentBond] = $fragment;
            $this->setAncestorsUnderFieldOrFragmentBond($fieldOrFragmentBond);
        }
    }
}

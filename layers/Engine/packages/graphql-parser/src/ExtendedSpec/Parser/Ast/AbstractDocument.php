<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedFieldValueReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\RuntimeVariableReferenceInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document as UpstreamDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractDocument extends UpstreamDocument
{
    /**
     * Do not validate if dynamic variables have been
     * defined in the Operation
     */
    protected function isVariableDefined(
        VariableReference $variableReference,
    ): bool {
        if ($variableReference instanceof RuntimeVariableReferenceInterface) {
            return true;
        }
        return parent::isVariableDefined($variableReference);
    }

    /**
     * @param Directive[] $directives
     * @return VariableReference[]
     */
    protected function getVariableReferencesInDirectives(array $directives): array
    {
        $variableReferences = parent::getVariableReferencesInDirectives($directives);
        /** @var MetaDirective[] */
        $metaDirectives = array_filter(
            $directives,
            fn (Directive $directive) => $directive instanceof MetaDirective
        );
        foreach ($metaDirectives as $metaDirective) {
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInDirectives($metaDirective->getNestedDirectives())
            );
        }
        return $variableReferences;
    }

    /**
     * @param Directive[] $directives
     * @throws InvalidRequestException
     */
    protected function assertArgumentsUniqueInDirectives(array $directives): void
    {
        parent::assertArgumentsUniqueInDirectives($directives);

        /** @var MetaDirective[] */
        $metaDirectives = array_filter(
            $directives,
            fn (Directive $directive) => $directive instanceof MetaDirective
        );
        foreach ($metaDirectives as $metaDirective) {
            $this->assertArgumentsUniqueInDirectives($metaDirective->getNestedDirectives());
        }
    }

    /**
     * @param SplObjectStorage<AstInterface,AstInterface> $astNodeAncestors
     */
    protected function setAncestorsUnderDirective(SplObjectStorage $astNodeAncestors, Directive $directive): void
    {
        parent::setAncestorsUnderDirective($astNodeAncestors, $directive);

        if ($directive instanceof MetaDirective) {
            /** @var MetaDirective */
            $metaDirective = $directive;
            foreach ($metaDirective->getNestedDirectives() as $nestedDirective) {
                $astNodeAncestors[$nestedDirective] = $directive;
                $this->setAncestorsUnderDirective($astNodeAncestors, $nestedDirective);
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    public function validate(): void
    {
        parent::validate();

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableDynamicVariables = $moduleConfiguration->enableDynamicVariables();
        if ($enableDynamicVariables) {
            $this->assertNonSharedVariableAndDynamicVariableNames();
        }

        $enableObjectResolvedFieldValueReferences = $moduleConfiguration->enableObjectResolvedFieldValueReferences();
        if ($enableObjectResolvedFieldValueReferences) {
            $this->assertNonSharedVariableAndResolvedFieldValueReferenceNames();
        }

        if ($enableDynamicVariables && $enableObjectResolvedFieldValueReferences) {
            $this->assertNonSharedDynamicVariableAndResolvedFieldValueReferenceNames();
        }

        /**
         * Validate that @depends(on:...) doesn't form loops,
         * and all operations exist
         */
        if ($moduleConfiguration->enableMultipleQueryExecution()) {
            // @todo Implement here
            $this->assertDependedUponOperationsExist();
            // $this->assertDependedUponOperationsDoNotFormLoop();
        }
    }

    /**
     * Validate that all throughout the GraphQL query,
     * no Dynamic Variable has the same name as a normal
     * (static) Variable.
     *
     * @throws InvalidRequestException
     */
    protected function assertNonSharedVariableAndDynamicVariableNames(): void
    {
        $variables = [];
        foreach ($this->getOperations() as $operation) {
            $variables = array_merge(
                $variables,
                $operation->getVariables()
            );
        }

        $dynamicVariableDefinitionArguments = $this->getDynamicVariableDefinitionArguments();

        /**
         * Organize by name and astNode, as to give the Location of the error.
         * Notice that only 1 Location is raised, even if the error happens
         * on multiple places.
         */
        $variableNames = [];
        foreach ($variables as $variable) {
            $variableNames[$variable->getName()] = $variable;
        }
        $dynamicVariableNames = [];
        foreach ($dynamicVariableDefinitionArguments as $dynamicVariableDefinitionArgument) {
            $dynamicVariableName = (string)$dynamicVariableDefinitionArgument->getValue();
            // If many AST nodes fail, and they have the same name, show the 1st one
            if (isset($dynamicVariableNames[$dynamicVariableName])) {
                continue;
            }
            $dynamicVariableNames[$dynamicVariableName] = $dynamicVariableDefinitionArgument;
        }

        /** @var array<string,Argument> */
        $sharedVariableNames = array_intersect_key(
            $dynamicVariableNames,
            $variableNames
        );
        if ($sharedVariableNames === []) {
            return;
        }

        $dynamicVariableName = key($sharedVariableNames);
        $dynamicVariableDefinitionArgument = $sharedVariableNames[$dynamicVariableName];
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                GraphQLExtendedSpecErrorFeedbackItemProvider::E7,
                [
                    $dynamicVariableName,
                ]
            ),
            $dynamicVariableDefinitionArgument->getValueAST()
        );
    }

    /**
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArguments(): array
    {
        $dynamicVariableDefinitionArguments = [];
        foreach ($this->getOperations() as $operation) {
            $dynamicVariableDefinitionArguments = array_merge(
                $dynamicVariableDefinitionArguments,
                $this->getDynamicVariableDefinitionArgumentsInOperation($operation),
            );
        }
        foreach ($this->getFragments() as $fragment) {
            $dynamicVariableDefinitionArguments = array_merge(
                $dynamicVariableDefinitionArguments,
                $this->getDynamicVariableDefinitionArgumentsInFragment($fragment),
            );
        }
        return $dynamicVariableDefinitionArguments;
    }

    /**
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInOperation(OperationInterface $operation): array
    {
        return array_merge(
            $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($operation->getFieldsOrFragmentBonds()),
            $this->getDynamicVariableDefinitionArgumentsInDirectives($operation->getDirectives()),
        );
    }

    /**
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInFragment(Fragment $fragment): array
    {
        return array_merge(
            $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($fragment->getFieldsOrFragmentBonds()),
            $this->getDynamicVariableDefinitionArgumentsInDirectives($fragment->getDirectives()),
        );
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments(array $fieldsOrFragmentBonds): array
    {
        $dynamicVariableDefinitionArguments = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $dynamicVariableDefinitionArguments = array_merge(
                    $dynamicVariableDefinitionArguments,
                    $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($inlineFragment->getFieldsOrFragmentBonds())
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $dynamicVariableDefinitionArguments = array_merge(
                $dynamicVariableDefinitionArguments,
                $this->getDynamicVariableDefinitionArgumentsInDirectives($field->getDirectives())
            );
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $dynamicVariableDefinitionArguments = array_merge(
                    $dynamicVariableDefinitionArguments,
                    $this->getDynamicVariableDefinitionArgumentsInFieldsOrInlineFragments($relationalField->getFieldsOrFragmentBonds())
                );
            }
        }
        return $dynamicVariableDefinitionArguments;
    }

    /**
     * @param Directive[] $directives
     * @return Argument[]
     */
    protected function getDynamicVariableDefinitionArgumentsInDirectives(array $directives): array
    {
        $dynamicVariableDefinitionArguments = [];
        foreach ($directives as $directive) {
            /**
             * Check if this Directive is a "DynamicVariableDefiner"
             */
            if (!$this->isDynamicVariableDefinerDirective($directive)) {
                continue;
            }
            /**
             * Get the Argument under which the Dynamic Variable is defined
             */
            $exportUnderVariableNameArgument = $this->getExportUnderVariableNameArgument($directive);
            if ($exportUnderVariableNameArgument === null) {
                continue;
            }
            /**
             * All success!
             */
            $dynamicVariableDefinitionArguments[] = $exportUnderVariableNameArgument;
        }
        return $dynamicVariableDefinitionArguments;
    }

    abstract protected function isDynamicVariableDefinerDirective(Directive $directive): bool;
    abstract protected function getExportUnderVariableNameArgument(Directive $directive): ?Argument;

    /**
     * Validate that all Resolved Field Value References
     * do not share the same name with a Variable
     *
     * @throws InvalidRequestException
     */
    protected function assertNonSharedVariableAndResolvedFieldValueReferenceNames(): void
    {
        foreach ($this->getOperations() as $operation) {
            $this->assertNonSharedVariableAndResolvedFieldValueReferenceNamesInOperation($operation);
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertNonSharedVariableAndResolvedFieldValueReferenceNamesInOperation(OperationInterface $operation): void
    {
        $variables = $operation->getVariables();
        $resolvedFieldValueReferences = $this->getObjectResolvedFieldValueReferencesInOperation($operation);

        /**
         * Organize by name and astNode, as to give the Location of the error.
         * Notice that only 1 Location is raised, even if the error happens
         * on multiple places.
         */
        $variableNames = [];
        foreach ($variables as $variable) {
            $variableNames[$variable->getName()] = $variable;
        }
        $resolvedFieldValueReferenceNames = [];
        foreach ($resolvedFieldValueReferences as $resolvedFieldValueReference) {
            $resolvedFieldValueReferenceName = $resolvedFieldValueReference->getName();
            // If many AST nodes fail, and they have the same name, show the 1st one
            if (isset($resolvedFieldValueReferenceNames[$resolvedFieldValueReferenceName])) {
                continue;
            }
            $resolvedFieldValueReferenceNames[$resolvedFieldValueReferenceName] = $resolvedFieldValueReference;
        }

        /** @var array<string,Argument> */
        $sharedVariableNames = array_intersect_key(
            $resolvedFieldValueReferenceNames,
            $variableNames
        );
        if ($sharedVariableNames === []) {
            return;
        }

        $resolvedFieldValueReferenceName = key($sharedVariableNames);
        $resolvedFieldValueReference = $sharedVariableNames[$resolvedFieldValueReferenceName];
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                GraphQLExtendedSpecErrorFeedbackItemProvider::E8,
                [
                    '$' . $resolvedFieldValueReferenceName,
                ]
            ),
            $resolvedFieldValueReference
        );
    }

    /**
     * @return ObjectResolvedFieldValueReference[]
     */
    protected function getObjectResolvedFieldValueReferencesInOperation(OperationInterface $operation): array
    {
        return $this->getObjectResolvedFieldValueReferencesInFieldsOrInlineFragments($operation->getFieldsOrFragmentBonds());
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @return ObjectResolvedFieldValueReference[]
     */
    protected function getObjectResolvedFieldValueReferencesInFieldsOrInlineFragments(array $fieldsOrFragmentBonds): array
    {
        $objectResolvedFieldValueReferences = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $objectResolvedFieldValueReferences = array_merge(
                    $objectResolvedFieldValueReferences,
                    $this->getObjectResolvedFieldValueReferencesInFieldsOrInlineFragments($inlineFragment->getFieldsOrFragmentBonds())
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $objectResolvedFieldValueReferences = array_merge(
                $objectResolvedFieldValueReferences,
                $this->getObjectResolvedFieldValueReferencesInArguments($field->getArguments())
            );
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $objectResolvedFieldValueReferences = array_merge(
                    $objectResolvedFieldValueReferences,
                    $this->getObjectResolvedFieldValueReferencesInFieldsOrInlineFragments($relationalField->getFieldsOrFragmentBonds())
                );
            }
        }
        return $objectResolvedFieldValueReferences;
    }

    /**
     * @param Argument[] $arguments
     * @return ObjectResolvedFieldValueReference[]
     */
    protected function getObjectResolvedFieldValueReferencesInArguments(array $arguments): array
    {
        $objectResolvedFieldValueReferences = [];
        foreach ($arguments as $argument) {
            $objectResolvedFieldValueReferences = array_merge(
                $objectResolvedFieldValueReferences,
                $this->getObjectResolvedFieldValueReferencesInArgumentValue($argument->getValueAST())
            );
        }
        return $objectResolvedFieldValueReferences;
    }

    /**
     * @param WithValueInterface|array<WithValueInterface|array<mixed>> $argumentValue
     * @return ObjectResolvedFieldValueReference[]
     */
    protected function getObjectResolvedFieldValueReferencesInArgumentValue(WithValueInterface|array $argumentValue): array
    {
        if ($argumentValue instanceof ObjectResolvedFieldValueReference) {
            return [$argumentValue];
        }
        if (!(is_array($argumentValue) || $argumentValue instanceof InputObject || $argumentValue instanceof InputList)) {
            return [];
        }

        // Get references within InputObjects and Lists
        $objectResolvedFieldValueReferences = [];
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
                if (!(is_array($listValue) || $listValue instanceof ObjectResolvedFieldValueReference || $listValue instanceof WithValueInterface)) {
                    continue;
                }
                /** @var WithValueInterface|mixed[] $listValue */
                $objectResolvedFieldValueReferences = array_merge(
                    $objectResolvedFieldValueReferences,
                    $this->getObjectResolvedFieldValueReferencesInArgumentValue($listValue)
                );
            }
            return $objectResolvedFieldValueReferences;
        }
        /** @var WithAstValueInterface $argumentValue */
        $listValues = (array)$argumentValue->getAstValue();
        foreach ($listValues as $listValue) {
            if (!(is_array($listValue) || $listValue instanceof ObjectResolvedFieldValueReference || $listValue instanceof WithValueInterface)) {
                continue;
            }
            if ($listValue instanceof ObjectResolvedFieldValueReference) {
                $objectResolvedFieldValueReferences[] = $listValue;
                continue;
            }
            /** @var WithValueInterface|mixed[] $listValue */
            $objectResolvedFieldValueReferences = array_merge(
                $objectResolvedFieldValueReferences,
                $this->getObjectResolvedFieldValueReferencesInArgumentValue($listValue)
            );
        }
        return $objectResolvedFieldValueReferences;
    }

    /**
     * Validate that all Resolved Field Value References
     * do not share the same name with a Dynamic Variable
     *
     * @throws InvalidRequestException
     */
    protected function assertNonSharedDynamicVariableAndResolvedFieldValueReferenceNames(): void
    {
        $dynamicVariableDefinitionArguments = $this->getDynamicVariableDefinitionArguments();
        $objectResolvedFieldValueReferences = $this->getObjectResolvedFieldValueReferences();

        /**
         * Organize by name and astNode, as to give the Location of the error.
         * Notice that only 1 Location is raised, even if the error happens
         * on multiple places.
         */
        $dynamicVariableNames = [];
        foreach ($dynamicVariableDefinitionArguments as $dynamicVariableDefinitionArgument) {
            $dynamicVariableName = (string)$dynamicVariableDefinitionArgument->getValue();
            // If many AST nodes fail, and they have the same name, show the 1st one
            if (isset($dynamicVariableNames[$dynamicVariableName])) {
                continue;
            }
            $dynamicVariableNames[$dynamicVariableName] = $dynamicVariableDefinitionArgument;
        }
        $objectResolvedFieldValueReferenceNames = [];
        foreach ($objectResolvedFieldValueReferences as $objectResolvedFieldValueReference) {
            $objectResolvedFieldValueReferenceName = $objectResolvedFieldValueReference->getName();
            // If many AST nodes fail, and they have the same name, show the 1st one
            if (isset($objectResolvedFieldValueReferenceNames[$objectResolvedFieldValueReferenceName])) {
                continue;
            }
            $objectResolvedFieldValueReferenceNames[$objectResolvedFieldValueReferenceName] = $objectResolvedFieldValueReference;
        }

        /** @var array<string,Argument> */
        $sharedVariableNames = array_intersect_key(
            $dynamicVariableNames,
            $objectResolvedFieldValueReferenceNames
        );
        if ($sharedVariableNames === []) {
            return;
        }

        $dynamicVariableName = key($sharedVariableNames);
        $dynamicVariableDefinitionArgument = $sharedVariableNames[$dynamicVariableName];
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                GraphQLExtendedSpecErrorFeedbackItemProvider::E9,
                [
                    $dynamicVariableName,
                    '$' . $dynamicVariableName,
                ]
            ),
            $dynamicVariableDefinitionArgument->getValueAST()
        );
    }

    /**
     * @return ObjectResolvedFieldValueReference[]
     */
    protected function getObjectResolvedFieldValueReferences(): array
    {
        $objectResolvedFieldValueReference = [];
        foreach ($this->getOperations() as $operation) {
            $objectResolvedFieldValueReference = array_merge(
                $objectResolvedFieldValueReference,
                $this->getObjectResolvedFieldValueReferencesInOperation($operation),
            );
        }
        foreach ($this->getFragments() as $fragment) {
            $objectResolvedFieldValueReference = array_merge(
                $objectResolvedFieldValueReference,
                $this->getObjectResolvedFieldValueReferencesInFragment($fragment),
            );
        }
        return $objectResolvedFieldValueReference;
    }

    /**
     * @return ObjectResolvedFieldValueReference[]
     */
    protected function getObjectResolvedFieldValueReferencesInFragment(Fragment $fragment): array
    {
        return $this->getObjectResolvedFieldValueReferencesInFieldsOrInlineFragments($fragment->getFieldsOrFragmentBonds());
    }

    /**
     * Validate that all Operations declared under
     * @depends(on:...) all exist
     *
     * @throws InvalidRequestException
     */
    protected function assertDependedUponOperationsExist(): void
    {
        $operationDependencyDefinitionArguments = $this->getOperationDependencyDefinitionArguments();
        // $objectResolvedFieldValueReferences = $this->getObjectResolvedFieldValueReferences();

        // /**
        //  * Organize by name and astNode, as to give the Location of the error.
        //  * Notice that only 1 Location is raised, even if the error happens
        //  * on multiple places.
        //  */
        // $dependendUponOperationNames = [];
        // foreach ($operationDependencyDefinitionArguments as $operationDependencyDefinitionArgument) {
        //     $dependendUponOperationName = (string)$operationDependencyDefinitionArgument->getValue();
        //     // If many AST nodes fail, and they have the same name, show the 1st one
        //     if (isset($dependendUponOperationNames[$dependendUponOperationName])) {
        //         continue;
        //     }
        //     $dependendUponOperationNames[$dependendUponOperationName] = $operationDependencyDefinitionArgument;
        // }
        // $objectResolvedFieldValueReferenceNames = [];
        // foreach ($objectResolvedFieldValueReferences as $objectResolvedFieldValueReference) {
        //     $objectResolvedFieldValueReferenceName = $objectResolvedFieldValueReference->getName();
        //     // If many AST nodes fail, and they have the same name, show the 1st one
        //     if (isset($objectResolvedFieldValueReferenceNames[$objectResolvedFieldValueReferenceName])) {
        //         continue;
        //     }
        //     $objectResolvedFieldValueReferenceNames[$objectResolvedFieldValueReferenceName] = $objectResolvedFieldValueReference;
        // }

        // /** @var array<string,Argument> */
        // $sharedVariableNames = array_intersect_key(
        //     $dependendUponOperationNames,
        //     $objectResolvedFieldValueReferenceNames
        // );
        // if ($sharedVariableNames === []) {
        //     return;
        // }

        // $dependendUponOperationName = key($sharedVariableNames);
        // $operationDependencyDefinitionArgument = $sharedVariableNames[$dependendUponOperationName];
        // throw new InvalidRequestException(
        //     new FeedbackItemResolution(
        //         GraphQLExtendedSpecErrorFeedbackItemProvider::class,
        //         GraphQLExtendedSpecErrorFeedbackItemProvider::E9,
        //         [
        //             $dependendUponOperationName,
        //             '$' . $dependendUponOperationName,
        //         ]
        //     ),
        //     $operationDependencyDefinitionArgument->getValueAST()
        // );
    }

    /**
     * @return Argument[]
     */
    protected function getOperationDependencyDefinitionArguments(): array
    {
        $operationDependencyDefinitionArguments = [];
        foreach ($this->getOperations() as $operation) {
            $operationDependencyDefinitionArguments = array_merge(
                $operationDependencyDefinitionArguments,
                $this->getOperationDependencyDefinitionArgumentsInOperation($operation),
            );
        }
        return $operationDependencyDefinitionArguments;
    }

    /**
     * @return Argument[]
     */
    protected function getOperationDependencyDefinitionArgumentsInOperation(OperationInterface $operation): array
    {
        return $this->getOperationDependencyDefinitionArgumentsInDirectives($operation->getDirectives());
    }

    /**
     * @param Directive[] $directives
     * @return Argument[]
     */
    protected function getOperationDependencyDefinitionArgumentsInDirectives(array $directives): array
    {
        $operationDependencyDefinitionArguments = [];
        foreach ($directives as $directive) {
            /**
             * Check if this Directive is a "OperationDependencyDefiner"
             */
            if (!$this->isOperationDependencyDefinerDirective($directive)) {
                continue;
            }
            /**
             * Get the Argument under which the Depended-upon Operation is defined
             */
            $provideDependedUponOperationNamesArgument = $this->getProvideDependedUponOperationNamesArgument($directive);
            if ($provideDependedUponOperationNamesArgument === null) {
                continue;
            }
            /**
             * All success!
             */
            $operationDependencyDefinitionArguments[] = $provideDependedUponOperationNamesArgument;
        }
        return $operationDependencyDefinitionArguments;
    }

    abstract protected function isOperationDependencyDefinerDirective(Directive $directive): bool;
    abstract protected function getProvideDependedUponOperationNamesArgument(Directive $directive): ?Argument;
}

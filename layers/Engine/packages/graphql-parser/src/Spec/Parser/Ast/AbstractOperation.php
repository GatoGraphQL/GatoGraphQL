<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractOperation extends AbstractAst implements OperationInterface
{
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;

    public function __construct(
        protected string $name,
        /** @var Variable[] */
        protected array $variables,
        /** @var Directive[] $directives */
        array $directives,
        /** @var FieldInterface[]|FragmentBondInterface[] */
        array $fieldsOrFragmentBonds,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Variable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Gather all the FragmentReference within the Operation.
     *
     * @param Fragment[] $fragments
     * @return FragmentReference[]
     */
    public function getFragmentReferences(array $fragments): array
    {
        return $this->getFragmentReferencesInFieldsOrFragmentBonds($this->fieldsOrFragmentBonds, $fragments);
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FragmentReference[]
     */
    protected function getFragmentReferencesInFieldsOrFragmentBonds(array $fieldsOrFragmentBonds, array $fragments): array
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
                    $this->getFragmentReferencesInFieldsOrFragmentBonds($fieldOrFragmentBond->getFieldsOrFragmentBonds(), $fragments)
                );
                continue;
            }
            /** @var FragmentReference */
            $fragmentReference = $fieldOrFragmentBond;
            $fragmentReferences[] = $fragmentReference;
            $fragment = $this->getFragment($fragments, $fragmentReference->getName());
            if ($fragment === null) {
                continue;
            }
            $fragmentReferences = array_merge(
                $fragmentReferences,
                $this->getFragmentReferencesInFieldsOrFragmentBonds($fragment->getFieldsOrFragmentBonds(), $fragments)
            );
        }
        return $fragmentReferences;
    }

    /**
     * Gather all the VariableReference within the Operation.
     *
     * @param Fragment[] $fragments
     * @return VariableReference[]
     */
    public function getVariableReferences(array $fragments): array
    {
        return array_merge(
            $this->getVariableReferencesInFieldsOrFragments($this->fieldsOrFragmentBonds, $fragments),
            $this->getVariableReferencesInDirectives($this->directives)
        );
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return VariableReference[]
     */
    protected function getVariableReferencesInFieldsOrFragments(array $fieldsOrFragmentBonds, array $fragments): array
    {
        $variableReferences = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragments, $fragmentReference->getName());
                if ($fragment === null) {
                    continue;
                }
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($fragment->getFieldsOrFragmentBonds(), $fragments)
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($inlineFragment->getFieldsOrFragmentBonds(), $fragments)
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
                    $this->getVariableReferencesInFieldsOrFragments($relationalField->getFieldsOrFragmentBonds(), $fragments)
                );
                continue;
            }
        }
        return $variableReferences;
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function getFragment(array $fragments, string $fragmentName): ?Fragment
    {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentName) {
                return $fragment;
            }
        }

        return null;
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
}

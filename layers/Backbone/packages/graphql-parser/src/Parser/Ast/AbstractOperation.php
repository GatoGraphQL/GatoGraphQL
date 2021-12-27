<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Location;

abstract class AbstractOperation extends AbstractAst implements OperationInterface
{
    use AstDirectivesTrait;

    public function __construct(
        protected string $name,
        /** @var Variable[] */
        protected array $variables,
        /** @var Directive[] $directives */
        array $directives,
        /** @var FieldInterface[]|FragmentBondInterface[] */
        protected array $fieldsOrFragmentBonds,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
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
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array
    {
        return $this->getFragmentReferencesInFieldsOrFragmentBonds($this->fieldsOrFragmentBonds);
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @return FragmentReference[]
     */
    protected function getFragmentReferencesInFieldsOrFragmentBonds(array $fieldsOrFragmentBonds): array
    {
        $fragmentReferences = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof LeafField) {
                continue;
            }
            if ($fieldOrFragmentReference instanceof InlineFragment) {
                /** @var InlineFragment */
                $typedFragmentReference = $fieldOrFragmentReference;
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->getFragmentReferencesInFieldsOrFragmentBonds($typedFragmentReference->getFieldsOrFragmentBonds())
                );
                continue;
            }
            if ($fieldOrFragmentReference instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $fieldOrFragmentReference;
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->getFragmentReferencesInFieldsOrFragmentBonds($relationalField->getFieldsOrFragmentBonds())
                );
                continue;
            }
            /** @var FragmentReference */
            $fragmentReference = $fieldOrFragmentReference;
            $fragmentReferences[] = $fragmentReference;
        }
        return $fragmentReferences;
    }

    /**
     * Gather all the VariableReference within the Operation.
     *
     * @return VariableReference[]
     */
    public function getVariableReferences(): array
    {
        return array_merge(
            $this->getVariableReferencesInFieldsOrFragments($this->fieldsOrFragmentBonds),
            $this->getVariableReferencesInDirectives($this->directives)
        );
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @return VariableReference[]
     */
    protected function getVariableReferencesInFieldsOrFragments(array $fieldsOrFragmentBonds): array
    {
        $variableReferences = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentReference instanceof InlineFragment) {
                /** @var InlineFragment */
                $typedFragmentReference = $fieldOrFragmentReference;
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($typedFragmentReference->getFieldsOrFragmentBonds())
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentReference;
            $variableReferences = array_merge(
                $variableReferences,
                $this->getVariableReferencesInArguments($field->getArguments())
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
     * @param Argument[] $arguments
     * @return VariableReference[]
     */
    protected function getVariableReferencesInArguments(array $arguments): array
    {
        $variableReferences = [];
        foreach ($arguments as $argument) {
            if (!($argument->getValue() instanceof VariableReference)) {
                continue;
            }
            /** @var VariableReference */
            $variableReference = $argument->getValue();
            $variableReferences[] = $variableReference;
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
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    public function getFieldsOrFragmentBonds(): array
    {
        return $this->fieldsOrFragmentBonds;
    }

    public function hasFieldsOrFragmentBonds(): bool
    {
        return count($this->fieldsOrFragmentBonds) > 0;
    }
}

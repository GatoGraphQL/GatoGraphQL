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
        /** @var FieldInterface[]|FragmentInterface[] */
        protected array $fieldOrFragmentReferences,
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
        return $this->getFragmentReferencesInFieldsOrFragmentReferences($this->fieldOrFragmentReferences);
    }

    /**
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferences
     * @return FragmentReference[]
     */
    protected function getFragmentReferencesInFieldsOrFragmentReferences(array $fieldOrFragmentReferences): array
    {
        $fragmentReferences = [];
        foreach ($fieldOrFragmentReferences as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof LeafField) {
                continue;
            }
            if ($fieldOrFragmentReference instanceof InlineFragment) {
                /** @var InlineFragment */
                $typedFragmentReference = $fieldOrFragmentReference;
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->getFragmentReferencesInFieldsOrFragmentReferences($typedFragmentReference->getFieldOrFragmentReferences())
                );
                continue;
            }
            if ($fieldOrFragmentReference instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $fieldOrFragmentReference;
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->getFragmentReferencesInFieldsOrFragmentReferences($relationalField->getFieldOrFragmentReferences())
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
            $this->getVariableReferencesInFieldsOrFragments($this->fieldOrFragmentReferences),
            $this->getVariableReferencesInDirectives($this->directives)
        );
    }

    /**
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferences
     * @return VariableReference[]
     */
    protected function getVariableReferencesInFieldsOrFragments(array $fieldOrFragmentReferences): array
    {
        $variableReferences = [];
        foreach ($fieldOrFragmentReferences as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentReference instanceof InlineFragment) {
                /** @var InlineFragment */
                $typedFragmentReference = $fieldOrFragmentReference;
                $variableReferences = array_merge(
                    $variableReferences,
                    $this->getVariableReferencesInFieldsOrFragments($typedFragmentReference->getFieldOrFragmentReferences())
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
                    $this->getVariableReferencesInFieldsOrFragments($relationalField->getFieldOrFragmentReferences())
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
     * @return FieldInterface[]|FragmentInterface[]
     */
    public function getFieldOrFragmentReferences(): array
    {
        return $this->fieldOrFragmentReferences;
    }

    public function hasFieldOrFragmentReferences(): bool
    {
        return count($this->fieldOrFragmentReferences) > 0;
    }
}

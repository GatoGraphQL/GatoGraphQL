<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

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
        return $this->addFragmentReferences([], $this->fieldOrFragmentReferences);
    }

    /**
     * @param FragmentReference[] $fragmentReferences
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferencesToIterate
     * @return FragmentReference[]
     */
    protected function addFragmentReferences(array $fragmentReferences, array $fieldOrFragmentReferencesToIterate): array
    {
        foreach ($fieldOrFragmentReferencesToIterate as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentReference;
                $fragmentReferences[] = $fragmentReference;
                continue;
            }
            if ($fieldOrFragmentReference instanceof TypedFragmentReference) {
                /** @var TypedFragmentReference */
                $typedFragmentReference = $fieldOrFragmentReference;
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->addFragmentReferences([], $typedFragmentReference->getFieldOrFragmentReferences)
                );
                continue;
            }
            if ($fieldOrFragmentReference instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $fieldOrFragmentReference;
                $fragmentReferences = array_merge(
                    $fragmentReferences,
                    $this->addFragmentReferences([], $relationalField->getFieldOrFragmentReferences)
                );
                continue;
            }
        }
        return $fragmentReferences;
    }

    // @todo Calculate deep
    /**
     * Gather all the VariableReference within the Operation.
     *
     * @return VariableReference[]
     */
    public function getVariableReferences(): array
    {
        $variableReferences = [];
        foreach ($this->fieldOrFragmentReferences as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof FieldInterface) {

            }
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

    public function hasField(string $name, bool $deep = false): bool
    {
        foreach ($this->getFieldOrFragmentReferences() as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof FragmentInterface) {
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentReference;
            if ($field->getName() === $name
                || ($deep && $field instanceof RelationalField && $field->hasField($name))
            ) {
                return true;
            }
        }

        return false;
    }
}

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

    // @todo Calculate deep
    /**
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array
    {
        return [];
    }

    // @todo Calculate deep
    /**
     * @return VariableReference[]
     */
    public function getVariableReferences(): array
    {
        return [];
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

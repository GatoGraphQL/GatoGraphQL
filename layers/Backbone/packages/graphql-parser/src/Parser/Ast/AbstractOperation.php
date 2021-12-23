<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

abstract class AbstractOperation extends AbstractAst implements OperationInterface
{
    use AstDirectivesTrait;

    public function __construct(
        protected string $name,
        /** @var FragmentReference[] */
        protected array $fragmentReferences,
        /** @var Variable[] */
        protected array $variables,
        /** @var VariableReference[] */
        protected array $variableReferences,
        /** @var Directive[] $directives */
        array $directives,
        /** @var FieldInterface[]|FragmentInterface[] */
        protected array $fields,
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
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array
    {
        return $this->fragmentReferences;
    }

    /**
     * @return Variable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return VariableReference[]
     */
    public function getVariableReferences(): array
    {
        return $this->variableReferences;
    }

    /**
     * @return FieldInterface[]|FragmentInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function hasFields(): bool
    {
        return count($this->fields) > 0;
    }

    public function hasField(string $name, bool $deep = false): bool
    {
        foreach ($this->getFields() as $field) {
            if ($field->getName() === $name
                || ($deep && $field instanceof RelationalField && $field->hasField($name))
            ) {
                return true;
            }
        }

        return false;
    }
}

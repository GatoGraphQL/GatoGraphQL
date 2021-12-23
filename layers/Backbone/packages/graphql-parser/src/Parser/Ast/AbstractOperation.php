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
        /** @var Field[]|Query[]|FragmentReference[]|TypedFragmentReference[] */
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
     * @return Variable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return Field[]|Query[]|FragmentInterface[]|TypedFragmentReference[]
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
            if ($field->getName() === $name) {
                return true;
            }

            if ($deep && $field instanceof Query) {
                if ($field->hasField($name)) {
                    return true;
                }
            }
        }

        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

class RelationalField extends AbstractAst implements FieldInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;

    /** @var FieldInterface[]|FragmentInterface[] */
    protected array $fields = [];

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentInterface[] $fields
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected ?string $alias,
        array $arguments,
        array $fields,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setFields($fields);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    public function getName(): string
    {
        return $this->name;
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

    /**
     * @param FieldInterface[]|FragmentInterface[] $fields
     */
    public function setFields(array $fields): void
    {
        /**
         * we cannot store fields by name because of TypedFragments
         */
        $this->fields = $fields;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function hasField(string $name, bool $deep = false): bool
    {
        foreach ($this->getFields() as $field) {
            if ($field->getName() === $name
                || ($deep && $field instanceof RelationalField) && $field->hasField($name)
            ) {
                return true;
            }
        }

        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\FieldInterface;
use PoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface;
use PoP\GraphQLParser\Parser\Location;

class Query extends AbstractAst implements FieldInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;

    /** @var Field[]|Query[] */
    protected array $fields = [];

    /**
     * @param Argument[] $arguments
     * @param Field[]|Query[]|FragmentReference[] $fields
     * @param Directive[] $directives
     */
    public function __construct(protected string $name, protected ?string $alias, array $arguments, array $fields, array $directives, Location $location)
    {
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
     * @return Field[]|Query[]|FragmentInterface[]
     */
    public function getFields(): array
    {
        return array_values($this->fields);
    }

    public function hasFields(): bool
    {
        return count($this->fields) > 0;
    }

    /**
     * @param Field[]|Query[] $fields
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

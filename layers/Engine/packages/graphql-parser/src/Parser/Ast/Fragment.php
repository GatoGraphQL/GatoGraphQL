<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Location;

class Fragment extends AbstractAst
{
    use AstDirectivesTrait;

    private bool $used = false;

    /**
     * @param Field[]|Query[] $fields
     */
    public function __construct(protected string $name, protected string $model, array $directives, protected array $fields, Location $location)
    {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): void
    {
        $this->used = $used;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @return Field[]|Query[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field[]|Query[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }
}

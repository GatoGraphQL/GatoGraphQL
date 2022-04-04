<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class LeafField extends AbstractAst implements FieldInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;
    use FieldTrait;

    protected RelationalField|Fragment|InlineFragment|OperationInterface $parent;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    public function asQueryString(): string
    {
        return $this->asFieldOutputQueryString();
    }

    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }
}

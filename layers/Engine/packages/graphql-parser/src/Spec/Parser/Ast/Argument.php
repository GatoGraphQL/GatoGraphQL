<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Argument extends AbstractAst
{
    protected FieldInterface|Directive $parent;

    public function __construct(
        protected string $name,
        protected WithValueInterface $value,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function asQueryString(): string
    {
        return sprintf(
            '%s: %s',
            $this->name,
            $this->value->asQueryString()
        );
    }

    public function setParent(FieldInterface|Directive $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): FieldInterface|Directive
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): WithValueInterface
    {
        return $this->value;
    }

    public function setValue(WithValueInterface $value): void
    {
        $this->value = $value;
    }
}

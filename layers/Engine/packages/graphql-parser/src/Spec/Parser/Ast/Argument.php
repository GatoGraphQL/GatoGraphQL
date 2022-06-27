<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Argument extends AbstractAst
{
    protected FieldInterface|Directive $parent;

    public function __construct(
        protected readonly string $name,
        protected WithValueInterface $value,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
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

    public function getValueAST(): WithValueInterface
    {
        return $this->value;
    }

    public function setValueAST(WithValueInterface $value): void
    {
        $this->value = $value;
    }

    final public function getValue(): mixed
    {
        return $this->value->getValue();
    }
}

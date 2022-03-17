<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Argument extends AbstractAst
{
    public function __construct(
        protected string $name,
        protected WithValueInterface $value,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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

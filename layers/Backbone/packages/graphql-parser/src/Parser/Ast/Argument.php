<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\WithValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class Argument extends AbstractAst
{
    public function __construct(
        private string $name,
        private WithValueInterface $value,
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

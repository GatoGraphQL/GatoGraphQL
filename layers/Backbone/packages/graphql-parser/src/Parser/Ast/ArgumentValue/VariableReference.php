<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\ValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class VariableReference extends AbstractAst implements ValueInterface
{
    private mixed $value;

    public function __construct(
        private string $name,
        private ?Variable $variable,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function getVariable(): ?Variable
    {
        return $this->variable;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

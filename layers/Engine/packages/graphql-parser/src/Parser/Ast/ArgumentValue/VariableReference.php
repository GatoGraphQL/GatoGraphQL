<?php

/**
 * Date: 10/24/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use PoP\GraphQLParser\Parser\Location;

class VariableReference extends AbstractAst implements ValueInterface
{
    private mixed $value;

    public function __construct(private string $name, private ?Variable $variable, Location $location)
    {
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

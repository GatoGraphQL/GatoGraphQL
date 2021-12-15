<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use PoP\GraphQLParser\Parser\Location;

class Literal extends AbstractAst implements ValueInterface
{
    /**
     * @param string|int|float|bool|null $value
     */
    public function __construct(private string|int|float|bool|null $value, Location $location)
    {
        parent::__construct($location);
    }

    /**
     * @return string|int|float|bool|null
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param string|int|float|bool|null $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}

<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use PoP\GraphQLParser\Parser\Location;

class Literal extends AbstractAst implements ValueInterface
{
    /**
     * @param string|int|float|bool $value
     */
    public function __construct(private string|int|float|bool $value, Location $location)
    {
        parent::__construct($location);
    }

    /**
     * @return string|int|float|bool
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param string|int|float|bool $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}

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
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value, Location $location)
    {
        parent::__construct($location);

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}

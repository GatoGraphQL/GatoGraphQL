<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue;

use GraphQLByPoP\GraphQLParser\Parser\Ast\AbstractAst;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;

class Literal extends AbstractAst implements ValueInterface
{

    private $value;

    /**
     * @param mixed $value
     * @param Location $location
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
    public function setValue($value)
    {
        $this->value = $value;
    }
}

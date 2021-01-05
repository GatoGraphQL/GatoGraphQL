<?php

/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue;

use GraphQLByPoP\GraphQLParser\Parser\Ast\AbstractAst;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;

class InputObject extends AbstractAst implements ValueInterface
{

    protected $object = [];

    /**
     * @param array    $object
     * @param Location $location
     */
    public function __construct(array $object, Location $location)
    {
        parent::__construct($location);

        $this->object = $object;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->object;
    }

    /**
     * @param array $value
     */
    public function setValue($value)
    {
        $this->object = $value;
    }
}

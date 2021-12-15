<?php

/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use PoP\GraphQLParser\Parser\Location;

class InputObject extends AbstractAst implements ValueInterface
{
    protected $object = [];

    public function __construct(object $object, Location $location)
    {
        parent::__construct($location);

        $this->object = $object;
    }

    /**
     * @return object
     */
    public function getValue()
    {
        return $this->object;
    }

    /**
     * @param object $value
     */
    public function setValue($value): void
    {
        $this->object = $value;
    }
}

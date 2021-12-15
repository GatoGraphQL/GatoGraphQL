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
use stdClass;

class InputObject extends AbstractAst implements ValueInterface
{
    public function __construct(protected stdClass $object, Location $location)
    {
        parent::__construct($location);
    }

    /**
     * @return stdClass
     */
    public function getValue(): mixed
    {
        return $this->object;
    }

    /**
     * @param stdClass $value
     */
    public function setValue(mixed $value): void
    {
        $this->object = $value;
    }
}

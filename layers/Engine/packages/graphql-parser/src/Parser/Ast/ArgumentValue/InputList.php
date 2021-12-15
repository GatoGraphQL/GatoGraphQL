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

class InputList extends AbstractAst implements ValueInterface
{
    protected $list = [];

    public function __construct(array $list, Location $location)
    {
        parent::__construct($location);

        $this->list = $list;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->list;
    }

    /**
     * @param array $value
     */
    public function setValue($value): void
    {
        $this->list = $value;
    }
}

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
    public function __construct(protected array $list, Location $location)
    {
        parent::__construct($location);
    }

    /**
     * @return array
     */
    public function getValue(): mixed
    {
        return $this->list;
    }

    /**
     * @param array $value
     */
    public function setValue(mixed $value): void
    {
        $this->list = $value;
    }
}

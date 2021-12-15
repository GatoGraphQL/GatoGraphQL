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
    /**
     * @param mixed[] $list
     */
    public function __construct(protected array $list, Location $location)
    {
        parent::__construct($location);
    }

    /**
     * @return mixed[]
     */
    public function getValue(): mixed
    {
        return $this->list;
    }

    /**
     * @param mixed[] $value
     */
    public function setValue(mixed $value): void
    {
        $this->list = $value;
    }
}

<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;
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

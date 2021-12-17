<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\ValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class InputList extends AbstractAst implements ValueInterface
{
    /**
     * @param mixed[] $list
     */
    public function __construct(
        protected array $list,
        Location $location,
    ) {
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

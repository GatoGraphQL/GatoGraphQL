<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Location;

class Enum extends AbstractAst implements ArgumentValueAstInterface
{
    protected InputList|InputObject|Argument $parent;

    public function __construct(
        protected string $enumValue,
        Location $location
    ) {
        parent::__construct($location);
    }

    public function asQueryString(): string
    {
        return $this->enumValue;
    }

    public function setParent(InputList|InputObject|Argument $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): InputList|InputObject|Argument
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getValue(): mixed
    {
        return $this->enumValue;
    }
}

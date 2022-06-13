<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Location;

class Literal extends AbstractAst implements ArgumentValueAstInterface
{
    protected InputList|InputObject|Argument $parent;

    public function __construct(
        protected readonly string|int|float|bool|null $value,
        Location $location
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getLiteralAsQueryString($this->value);
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
     * @return string|int|float|bool|null
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}

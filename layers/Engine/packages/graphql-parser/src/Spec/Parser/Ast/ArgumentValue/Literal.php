<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Location;

class Literal extends AbstractAst implements CoercibleArgumentValueAstInterface
{
    public function __construct(
        protected string|int|float|bool|null $value,
        Location $location
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getLiteralAsQueryString($this->value);
    }

    /**
     * @return string|int|float|bool|null
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param string|int|float|bool|null $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}

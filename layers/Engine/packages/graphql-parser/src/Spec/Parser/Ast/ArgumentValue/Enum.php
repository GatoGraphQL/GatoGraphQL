<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class Enum extends AbstractAst implements WithValueInterface
{
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

    /**
     * @return string
     */
    public function getValue(): mixed
    {
        return $this->enumValue;
    }

    /**
     * @param string $enumValue
     */
    public function setValue(mixed $enumValue): void
    {
        $this->enumValue = $enumValue;
    }
}

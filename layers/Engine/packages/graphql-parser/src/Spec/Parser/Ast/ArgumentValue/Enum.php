<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Location;

class Enum extends AbstractAst implements ArgumentValueAstInterface
{
    public function __construct(
        protected string $enumValue,
        Location $location
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return $this->enumValue;
    }

    protected function doAsASTNodeString(): string
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
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     */
    public function isEquivalentTo(Enum $enum): bool
    {
        return $this->getValue() === $enum->getValue();
    }
}

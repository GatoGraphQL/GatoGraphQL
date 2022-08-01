<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Argument extends AbstractAst
{
    public function __construct(
        protected readonly string $name,
        protected WithValueInterface $value,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return sprintf(
            '%s: %s',
            $this->name,
            $this->value->asQueryString()
        );
    }

    protected function doAsASTNodeString(): string
    {
        return sprintf(
            '(%s: %s)',
            $this->name,
            $this->value->asQueryString()
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValueAST(): WithValueInterface
    {
        return $this->value;
    }

    final public function getValue(): mixed
    {
        return $this->value->getValue();
    }
}

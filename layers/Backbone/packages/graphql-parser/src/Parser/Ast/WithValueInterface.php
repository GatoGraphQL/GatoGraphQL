<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

interface WithValueInterface
{
    public function getValue(): mixed;

    public function setValue(mixed $value): void;
}

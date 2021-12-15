<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\Interfaces;

interface ValueInterface
{
    public function getValue(): mixed;

    public function setValue(mixed $value): void;
}

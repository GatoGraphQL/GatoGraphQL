<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface TraceInterface
{
    public function getID(): string|int;
    /**
     * @return array<string,mixed>
     */
    public function getData(): array;
    public function getRelationalTypeResolver(): ?RelationalTypeResolverInterface;
    public function getField(): ?string;
    public function getObjectID(): string|int|null;
    public function getDirective(): ?string;
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Tracing;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface TraceInterface
{
    public function getID(): string|int;
    /**
     * @return array<string,mixed>
     */
    public function getData(): array;
    /**
     * @return array<string|int,string[]>|null
     */
    public function getIDFields(): ?array;
    public function getDirective(): ?string;
    public function getRelationalTypeResolver(): ?RelationalTypeResolverInterface;
    public function getField(): ?string;
    public function getObjectID(): string|int|null;
}

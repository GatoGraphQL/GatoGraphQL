<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Tracing;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface TraceInterface
{
    public function getID(): string|int;
    /**
     * @return array<string,mixed>
     */
    public function getData(): array;
    /**
     * @return array<string|int,FieldInterface[]>|null
     */
    public function getIDFields(): ?array;
    public function getDirective(): ?string;
    public function getRelationalTypeResolver(): ?RelationalTypeResolverInterface;
    public function getField(): ?FieldInterface;
    public function getObjectID(): string|int|null;
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface ObjectTraceInterface extends TraceInterface
{
    public function getRelationalTypeResolver(): RelationalTypeResolverInterface;
    public function getField(): string;
    public function getObjectID(): string | int;
    public function getDirective(): ?string;
}

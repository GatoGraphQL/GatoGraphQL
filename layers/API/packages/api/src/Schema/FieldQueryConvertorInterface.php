<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\API\Schema\FieldQuerySet;

interface FieldQueryConvertorInterface
{
    public function convertAPIQuery(string $dotNotation, ?array $fragments = null): FieldQuerySet;
}

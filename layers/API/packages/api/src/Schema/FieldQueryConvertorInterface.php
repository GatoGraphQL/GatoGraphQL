<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

interface FieldQueryConvertorInterface
{
    public function convertAPIQuery(string $dotNotation, ?array $fragments = null): FieldQuerySet;
}

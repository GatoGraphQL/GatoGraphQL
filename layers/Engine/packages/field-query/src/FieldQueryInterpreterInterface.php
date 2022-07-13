<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

interface FieldQueryInterpreterInterface
{
    public function isFieldArgumentValueAnExpression(mixed $fieldArgValue): bool;
}

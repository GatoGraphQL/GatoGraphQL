<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use stdClass;

interface FieldQueryInterpreterInterface
{
    public function getFieldName(string $field): string;
    public function getFieldArgs(string $field): ?string;
    public function isFieldArgumentValueAField(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueAVariable(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueAnExpression(mixed $fieldArgValue): bool;
}

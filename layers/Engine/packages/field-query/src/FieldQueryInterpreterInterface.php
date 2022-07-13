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
    public function isFieldArgumentValueDynamic(mixed $fieldArgValue): bool;
    /**
     * Return an array with the position where the alias starts (including the "@") and its length
     * Return null if the field has no alias
     *
     * @return array<string, int>|null
     */
    public function getFieldAliasPositionSpanInField(string $field): ?array;
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

class ErrorCodes
{
    // public const NO_DIRECTIVE = 'no-directive';
    public const NON_NULLABLE_FIELD = 'non-nullable-field';
    public const MUST_NOT_BE_ARRAY_FIELD = 'must-not-be-array-field';
    public const MUST_BE_ARRAY_FIELD = 'must-be-array-field';
    public const ARRAY_MUST_NOT_HAVE_EMPTY_ITEMS_FIELD = 'array-must-not-have-empty-field';
    public const MUST_BE_ARRAY_OF_ARRAYS_FIELD = 'must-be-array-of-arrays-field';
    public const MUST_NOT_BE_ARRAY_OF_ARRAYS_FIELD = 'must-not-be-array-of-arrays-field';
    public const ARRAY_OF_ARRAYS_MUST_NOT_HAVE_EMPTY_ITEMS_FIELD = 'array-of-arrays-must-not-have-empty-field';
    public const NO_FIELD = 'no-field';
    public const VALIDATION_FAILED = 'validation-failed';
    public const NO_FIELD_RESOLVER_UNIT_PROCESSES_FIELD = 'no-fieldresolverunit-processes-field';
    public const NESTED_SCHEMA_ERRORS = 'nested-schema-errors';
    public const NESTED_DB_ERRORS = 'nested-db-errors';
    public const NESTED_ERRORS = 'nested-errors';
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

class ErrorCodes
{
    // public const NO_DIRECTIVE = 'no-directive';
    public const NON_NULLABLE_FIELD = 'non-nullable-field';
    public const NO_FIELD = 'no-field';
    public const VALIDATION_FAILED = 'validation-failed';
    public const NO_FIELD_RESOLVER_UNIT_PROCESSES_FIELD = 'no-fieldresolverunit-processes-field';
    public const NESTED_SCHEMA_ERRORS = 'nested-schema-errors';
    public const NESTED_DB_ERRORS = 'nested-db-errors';
    public const NESTED_ERRORS = 'nested-errors';
}

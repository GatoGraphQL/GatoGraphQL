<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

class ErrorCodes
{
    // public const NODIRECTIVE = 'no-directive';
    public const NONNULLABLEFIELD = 'non-nullable-field';
    public const NOFIELD = 'no-field';
    public const VALIDATIONFAILED = 'validation-failed';
    public const NOFIELDRESOLVERUNITPROCESSESFIELD = 'no-fieldresolverunit-processes-field';
    public const NESTEDSCHEMAERRORS = 'nested-schema-errors';
    public const NESTEDDBERRORS = 'nested-db-errors';
    public const NESTEDERRORS = 'nested-errors';
}

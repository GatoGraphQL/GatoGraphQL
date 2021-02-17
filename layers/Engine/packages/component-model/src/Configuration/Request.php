<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Schema\SchemaDefinition;

class Request
{
    /**
     * What version constraint to use for the API
     */
    public const URLPARAM_VERSION_CONSTRAINT = SchemaDefinition::ARGNAME_VERSION_CONSTRAINT;
    public const URLPARAM_VERSION_CONSTRAINT_FOR_FIELDS = 'fieldVersionConstraints';
    public const URLPARAM_VERSION_CONSTRAINT_FOR_DIRECTIVES = 'directiveVersionConstraints';

    /**
     * Indicates the version constraint for all fields/directives in the query
     *
     * @return string|null
     */
    public static function getVersionConstraint(): ?string
    {
        return $_REQUEST[self::URLPARAM_VERSION_CONSTRAINT] ?? null;
    }

    /**
     * Indicates the version constraints for specific fields in the schema
     *
     * @return string|null
     */
    public static function getVersionConstraintsForFields(): ?array
    {
        return $_REQUEST[self::URLPARAM_VERSION_CONSTRAINT_FOR_FIELDS] ?? null;
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     *
     * @return string|null
     */
    public static function getVersionConstraintsForDirectives(): ?array
    {
        return $_REQUEST[self::URLPARAM_VERSION_CONSTRAINT_FOR_DIRECTIVES] ?? null;
    }
}

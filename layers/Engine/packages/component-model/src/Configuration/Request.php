<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Schema\SchemaDefinition;

class Request
{
    const URLPARAM_MANGLED = 'mangled';
    const URLPARAMVALUE_MANGLED_NONE = 'none';
    /**
     * What version constraint to use for the API
     */
    const URLPARAM_VERSION_CONSTRAINT = SchemaDefinition::ARGNAME_VERSION_CONSTRAINT;
    const URLPARAM_VERSION_CONSTRAINT_FOR_FIELDS = 'fieldVersionConstraints';
    const URLPARAM_VERSION_CONSTRAINT_FOR_DIRECTIVES = 'directiveVersionConstraints';

    public static function isMangled(): bool
    {
        // By default, it is mangled, if not mangled then param "mangled" must have value "none"
        // Coment Leo 13/01/2017: getVars() can't function properly since it references objects which have not been initialized yet,
        // when called at the very beginning. So then access the request directly
        return !isset($_REQUEST[self::URLPARAM_MANGLED]) || !$_REQUEST[self::URLPARAM_MANGLED] || $_REQUEST[self::URLPARAM_MANGLED] != self::URLPARAMVALUE_MANGLED_NONE;
    }

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

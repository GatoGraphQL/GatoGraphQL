<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\Params;

class Request
{
    /**
     * @return string[]
     */
    public static function getActions(): array
    {
        return $_REQUEST[Params::ACTIONS] ?? [];
    }

    public static function getActionPath(): ?string
    {
        return $_REQUEST[Params::ACTION_PATH] ?? null;
    }

    /**
     * Indicates the version constraint for all fields/directives in the query
     */
    public static function getVersionConstraint(): ?string
    {
        return $_REQUEST[Params::VERSION_CONSTRAINT] ?? null;
    }

    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public static function getVersionConstraintsForFields(): ?array
    {
        return $_REQUEST[Params::VERSION_CONSTRAINT_FOR_FIELDS] ?? null;
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public static function getVersionConstraintsForDirectives(): ?array
    {
        return $_REQUEST[Params::VERSION_CONSTRAINT_FOR_DIRECTIVES] ?? null;
    }
}

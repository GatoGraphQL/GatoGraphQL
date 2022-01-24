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
        return $_POST[Params::ACTIONS] ?? $_GET[Params::ACTIONS] ?? [];
    }

    public static function getActionPath(): ?string
    {
        return $_POST[Params::ACTION_PATH] ?? $_GET[Params::ACTION_PATH] ?? null;
    }

    /**
     * Indicates the version constraint for all fields/directives in the query
     */
    public static function getVersionConstraint(): ?string
    {
        return $_POST[Params::VERSION_CONSTRAINT] ?? $_GET[Params::VERSION_CONSTRAINT] ?? null;
    }

    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public static function getVersionConstraintsForFields(): ?array
    {
        return $_POST[Params::VERSION_CONSTRAINT_FOR_FIELDS] ?? $_GET[Params::VERSION_CONSTRAINT_FOR_FIELDS] ?? null;
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public static function getVersionConstraintsForDirectives(): ?array
    {
        return $_POST[Params::VERSION_CONSTRAINT_FOR_DIRECTIVES] ?? $_GET[Params::VERSION_CONSTRAINT_FOR_DIRECTIVES] ?? null;
    }

    /**
     * @return string[]
     */
    public static function getExtraRoutes(): array
    {
        $extraRoutes = $_POST[Params::EXTRA_ROUTES] ?? $_GET[Params::EXTRA_ROUTES] ?? [];
        if (!is_array($extraRoutes)) {
            return [$extraRoutes];
        }
        return $extraRoutes;
    }

    public static function getModuleFilter(): ?string
    {
        return $_POST[Params::MODULEFILTER] ?? $_GET[Params::MODULEFILTER] ?? null;
    }

    /**
     * @return string[]
     */
    public static function getModulePaths(): array
    {
        $modulePaths = $_POST[Params::MODULEPATHS] ?? $_GET[Params::MODULEPATHS] ?? [];
        if (!is_array($modulePaths)) {
            return [$modulePaths];
        }
        return $modulePaths;
    }
}

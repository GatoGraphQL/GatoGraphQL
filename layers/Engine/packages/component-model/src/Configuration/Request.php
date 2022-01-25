<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\Params;
use PoP\Root\App;

class Request
{
    /**
     * @return string[]
     */
    public static function getActions(): array
    {
        return App::getRequest()->post->all()[Params::ACTIONS] ?? App::getRequest()->query->all()[Params::ACTIONS] ?? [];
    }

    public static function getActionPath(): ?string
    {
        return App::request(Params::ACTION_PATH) ?? App::query(Params::ACTION_PATH);
    }

    /**
     * Indicates the version constraint for all fields/directives in the query
     */
    public static function getVersionConstraint(): ?string
    {
        return App::request(Params::VERSION_CONSTRAINT) ?? App::query(Params::VERSION_CONSTRAINT);
    }

    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public static function getVersionConstraintsForFields(): ?array
    {
        return App::request(Params::VERSION_CONSTRAINT_FOR_FIELDS) ?? App::query(Params::VERSION_CONSTRAINT_FOR_FIELDS);
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public static function getVersionConstraintsForDirectives(): ?array
    {
        return App::request(Params::VERSION_CONSTRAINT_FOR_DIRECTIVES) ?? App::query(Params::VERSION_CONSTRAINT_FOR_DIRECTIVES);
    }

    /**
     * @return string[]
     */
    public static function getExtraRoutes(): array
    {
        $extraRoutes = App::getRequest()->post->all()[Params::EXTRA_ROUTES] ?? App::getRequest()->query->all()[Params::EXTRA_ROUTES] ?? [];
        if (!is_array($extraRoutes)) {
            return [$extraRoutes];
        }
        return $extraRoutes;
    }

    public static function getModuleFilter(): ?string
    {
        return App::request(Params::MODULEFILTER) ?? App::query(Params::MODULEFILTER);
    }

    /**
     * @return string[]
     */
    public static function getModulePaths(): array
    {
        $modulePaths = App::getRequest()->post->all()[Params::MODULEPATHS] ?? App::getRequest()->query->all()[Params::MODULEPATHS] ?? [];
        if (!is_array($modulePaths)) {
            return [$modulePaths];
        }
        return $modulePaths;
    }
}

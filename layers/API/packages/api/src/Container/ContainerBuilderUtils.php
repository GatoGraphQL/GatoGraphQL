<?php

declare(strict_types=1);

namespace PoP\API\Container;

use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;

class ContainerBuilderUtils
{

    /**
     * Add a predefined fragment to the catalogue
     *
     * @param string $injectableServiceId
     * @param string $value
     * @param string $methodCall
     * @return void
     */
    public static function addFragmentToCatalogueService(
        string $fragmentName,
        string $fragmentResolution,
        ?string $description = null
    ): void {
        // Format the fragment: Remove the tabs and new lines
        $fragmentResolution = PersistedQueryUtils::removeWhitespaces($fragmentResolution);
        // Enable using expressions, by going around an incompatibility with Symfony's DependencyInjection component
        $fragmentResolution = PersistedQueryUtils::addSpacingToExpressions($fragmentResolution);
        // Inject the values into the service
        \PoP\Root\Container\ContainerBuilderUtils::injectValuesIntoService(
            PersistedFragmentManagerInterface::class,
            'add',
            $fragmentName,
            $fragmentResolution,
            $description
        );
    }

    /**
     * Add a predefined query to the catalogue
     *
     * @param string $injectableServiceId
     * @param string $value
     * @param string $methodCall
     * @return void
     */
    public static function addQueryToCatalogueService(
        string $queryName,
        string $queryResolution,
        ?string $description = null
    ): void {
        // Format the query: Remove the tabs and new lines
        $queryResolution = PersistedQueryUtils::removeWhitespaces($queryResolution);
        // Enable using expressions, by going around an incompatibility with Symfony's DependencyInjection component
        $queryResolution = PersistedQueryUtils::addSpacingToExpressions($queryResolution);
        // Inject the values into the service
        \PoP\Root\Container\ContainerBuilderUtils::injectValuesIntoService(
            PersistedQueryManagerInterface::class,
            'add',
            $queryName,
            $queryResolution,
            $description
        );
    }
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class GatoGraphQL
{
    private static ?EndpointHelpers $endpointHelpers = null;

    final protected static function getEndpointHelpers(): EndpointHelpers
    {
        if (self::$endpointHelpers === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var EndpointHelpers */
            $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
            self::$endpointHelpers = $endpointHelpers;
        }
        return self::$endpointHelpers;
    }

    /**
     * Obtain the URL of the private wp-admin endpoint
     * (which powers the GraphiQL/Interactive Schema clients):
     *
     *   /wp-admin/edit.php?page=gatographql&action=execute_query
     *
     * This endpoint is affected by the configuration selected in the
     * Settings page, for the selected Schema Configuration for the
     * private endpoint, and by the selected default values.
     */
    final public static function getAdminEndpoint(): string
    {
        return self::getEndpointHelpers()->getAdminGraphQLEndpoint();
    }

    /**
     * Obtain the private wp-admin endpoint used by blocks in the
     * WordPress block editor (allowing any application, theme
     * or plugin to use GraphQL to fetch data for their blocks):
     *
     *   /wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=blockEditor
     *
     * This endpoint is NOT affected by the configuration selected in the
     * Settings page; it has a pre-defined non-restrictive configuration,
     * allowing to fetch any piece of data (eg: any CPT, any settings from
     * `wp_options`, any meta entry, etc).
     */
    final public static function getAdminBlockEditorEndpoint(): string
    {
        return self::getEndpointHelpers()->getAdminBlockEditorGraphQLEndpoint();
    }

    /**
     * Obtain a custom private wp-admin endpoint:
     *
     *   /wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group={customEndpointGroup}
     *
     * This custom endpoint must be defined and configured via PHP code,
     * allowing the developer to expose a private endpoint that has a
     * specific configuration, defined via code, to power the application,
     * theme or plugin.
     *
     * See the Tutorial section to learn how to define a custom private endpoint.
     */
    final public static function getAdminCustomEndpoint(string $endpointGroup): string
    {
        return self::getEndpointHelpers()->getAdminGraphQLEndpoint($endpointGroup);
    }
}

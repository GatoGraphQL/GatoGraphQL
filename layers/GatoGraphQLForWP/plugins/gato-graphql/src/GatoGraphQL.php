<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

final class GatoGraphQL
{
    private static ?EndpointHelpers $endpointHelpers = null;

    final static private function getEndpointHelpers(): EndpointHelpers
    {
        if (self::$endpointHelpers === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var EndpointHelpers */
            self::$endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        }
        return self::$endpointHelpers;
    }

    /**
     * Obtain the URL of the private wp-admin endpoint
     * (which powers the GraphiQL/Interactive Schema clients):
     *
     *   /wp-admin/edit.php?page=gato_graphql&action=execute_query
     *
     * This endpoint is affected by the configuration selected in the
     * Settings page (for the selected Schema Configuration and/or
     * default values).
     */
    public static function getAdminEndpoint(): string
    {
        return static::getEndpointHelpers()->getAdminGraphQLEndpoint();
    }

    /**
     * Obtain the private wp-admin endpoint used by blocks in the
     * WordPress block editor (allowing any application, theme
     * or plugin to use GraphQL to fetch data for their blocks):
     *
     *   /wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=blockEditor
     *
     * This endpoint is NOT affected by the configuration selected in the
     * Settings page; it has a pre-defined non-restrictive configuration,
     * allowing to fetch any piece of data.
     */
    public static function getAdminBlockEditorEndpoint(): string
    {
        return static::getEndpointHelpers()->getAdminBlockEditorGraphQLEndpoint();
    }

    /**
     * Obtain a custom private wp-admin endpoint:
     *
     *   /wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group={customEndpointGroup}
     *
     * This custom endpoint must be defined and configured via PHP code,
     * allowing the developer to expose a private endpoint that has a
     * specific configuration, defined via code.
     *
     * See the Recipes section to learn how to define a custom private endpoint.
     */
    public static function getAdminCustomEndpoint(string $endpointGroup): string
    {
        return static::getEndpointHelpers()->getAdminGraphQLEndpoint($endpointGroup);
    }
}

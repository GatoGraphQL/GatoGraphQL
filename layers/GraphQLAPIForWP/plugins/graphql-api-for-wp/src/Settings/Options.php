<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

/**
 * Option names. By convention they must all start with "graphql-api-"
 */
class Options
{
    /**
     * Option name under which to store the "GraphQL API" Settings, defined by the user
     */
    public final const GRAPHQL_API_SETTINGS = 'graphql-api-settings';
    /**
     * Option name under which to store the endpoint and client paths, defined by the user
     */
    public final const ACCESS_PATHS = 'access-paths';
    /**
     * Option name under which to store the Plugin Settings, defined by the user
     */
    public final const PLUGIN_SETTINGS = 'graphql-api-plugin-settings';
    /**
     * Option name for Plugin Management.
     *
     * This option won't be actually stored to DB, but it's
     * still needed to render the corresponding form.
     */
    public final const PLUGIN_MANAGEMENT = 'graphql-api-plugin-management';
    /**
     * Option name under which to store the enabled/disabled Modules
     */
    public final const MODULES = 'graphql-api-modules';
    /**
     * Option name under which to store the timestamps from the last
     * settings/modules write to the DB
     */
    public final const TIMESTAMPS = 'graphql-api-timestamps';
}

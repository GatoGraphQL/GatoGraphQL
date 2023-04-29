<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

/**
 * Option names. By convention they must all start with "graphql-api-"
 */
class Options
{
    /**
     * Option name under which to store the "Schema Configuration" values, defined by the user
     */
    public final const SCHEMA_CONFIGURATION = 'graphql-api-schema-configuration';
    /**
     * Option name under which to store the endpoint and client paths, defined by the user
     */
    public final const ENDPOINT_CONFIGURATION = 'graphql-api-endpoint-configuration';
    /**
     * Option name under which to store the Plugin Configuration, defined by the user
     */
    public final const PLUGIN_CONFIGURATION = 'graphql-api-plugin-configuration';
    /**
     * Option name under which to store the License Keys, defined by the user
     */
    public final const LICENSE_KEYS = 'graphql-api-license-keys';
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

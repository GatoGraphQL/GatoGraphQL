<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

/**
 * Option names. By convention they must all start with "gato-graphql-"
 */
class Options
{
    /**
     * Option name under which to store the "Schema Configuration" values, defined by the user
     */
    public final const SCHEMA_CONFIGURATION = 'gato-graphql-schema-configuration';
    /**
     * Option name under which to store the endpoint and client paths, defined by the user
     */
    public final const ENDPOINT_CONFIGURATION = 'gato-graphql-endpoint-configuration';
    /**
     * Option name under which to store the Plugin Configuration, defined by the user
     */
    public final const PLUGIN_CONFIGURATION = 'gato-graphql-plugin-configuration';
    /**
     * Option name under which to store the License Keys, defined by the user
     */
    public final const LICENSE_KEYS = 'gato-graphql-license-keys';
    /**
     * Option name for Plugin Management.
     *
     * This option won't be actually stored to DB, but it's
     * still needed to render the corresponding form.
     */
    public final const PLUGIN_MANAGEMENT = 'gato-graphql-plugin-management';
    /**
     * Option name under which to store the enabled/disabled Modules
     */
    public final const MODULES = 'gato-graphql-modules';
    /**
     * Option name under which to store the timestamps from the last
     * settings/modules write to the DB
     */
    public final const TIMESTAMPS = 'gato-graphql-timestamps';
}

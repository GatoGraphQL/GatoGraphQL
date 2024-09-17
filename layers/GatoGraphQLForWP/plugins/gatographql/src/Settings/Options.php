<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

/**
 * Option names. By convention they must all start with "gatographql-"
 */
class Options
{
    /**
     * Option name under which to store the endpoint and client paths, defined by the user
     */
    public final const ENDPOINT_CONFIGURATION = 'gatographql-endpoint-configuration';
    /**
     * Option name under which to store the "Schema Configuration" values, defined by the user
     */
    public final const SCHEMA_CONFIGURATION = 'gatographql-schema-configuration';
    /**
     * Option name under which to store the "Schema Type Configuration" values, defined by the user
     */
    public final const SCHEMA_TYPE_CONFIGURATION = 'gatographql-schema-type-configuration';
    /**
     * Option name under which to store the server configuration, defined by the user
     */
    public final const SERVER_CONFIGURATION = 'gatographql-server-configuration';
    /**
     * Option name under which to store the Plugin Configuration, defined by the user
     */
    public final const PLUGIN_CONFIGURATION = 'gatographql-plugin-configuration';
    /**
     * Option name under which to store the License Keys, defined by the user
     */
    public final const API_KEYS = 'gatographql-api-keys';
    /**
     * Option name for Plugin Management.
     *
     * This option won't be actually stored to DB, but it's
     * still needed to render the corresponding form.
     */
    public final const PLUGIN_MANAGEMENT = 'gatographql-plugin-management';
    /**
     * Option name under which to store the enabled/disabled Modules
     */
    public final const MODULES = 'gatographql-modules';
    /**
     * Option name under which to store the timestamps from the last
     * settings/modules write to the DB
     */
    public final const TIMESTAMPS = 'gatographql-timestamps';
    /**
     * Store the license data for all bundles/extensions that
     * have been activated
     */
    public final const COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES = 'gatographql-commercial-extension-activated-license-entries';
}

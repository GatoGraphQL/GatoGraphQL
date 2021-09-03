<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

/**
 * Option names
 */
class Options
{
    /**
     * Option name under which to store the Settings, defined by the user
     */
    public const SETTINGS = 'graphql-api-settings';
    /**
     * Option name under which to store the enabled/disabled Modules
     */
    public const MODULES = 'graphql-api-modules';
    /**
     * Option name under which to store the timestamps from the last
     * settings/modules write to the DB
     */
    public const TIMESTAMPS = 'graphql-api-timestamps';
}

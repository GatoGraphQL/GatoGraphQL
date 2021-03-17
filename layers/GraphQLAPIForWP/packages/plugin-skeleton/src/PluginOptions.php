<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

class PluginOptions
{
    /**
     * Store the plugin version in the Options table, to track when
     * the plugin is installed/updated
     */
    public const PLUGIN_VERSION = 'graphql-api-plugin-version';

    /**
     * Store when an extension is activated in the Options table,
     * to flush the rewrite rules.
     *
     * Watch out: when registering a new extension,
     * this value will be added manually, not via the const.
     * Then, do NOT change this value!
     */
    public const ACTIVATED_EXTENSION = 'graphql-api-extension';
}

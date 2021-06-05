<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

class PluginOptions
{
    /**
     * Store the plugin/extension versions in the Options table,
     * to track when each of them is installed/updated.
     *
     * It is stored as an array of "plugin/extension baseName" => "version"
     */
    public const PLUGIN_VERSIONS = 'graphql-api-plugin-versions';

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

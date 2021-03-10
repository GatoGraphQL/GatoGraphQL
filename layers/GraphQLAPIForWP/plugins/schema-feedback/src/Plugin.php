<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback;

use PoP\Root\Container\ContainerBuilderUtils;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class Plugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Get permalinks to work when activating the plugin
     *
     * @see    https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
     * @return void
     */
    public function activate(): void
    {
        // First, initialize all the custom post types
        $instanceManager = InstanceManagerFacade::getInstance();
        $postTypeObjects = array_map(
            function ($serviceClass) use ($instanceManager) {
                return $instanceManager->getInstance($serviceClass);
            },
            ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\PostTypes')
        );
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->registerPostType();
        }

        // Then, flush rewrite rules
        \flush_rewrite_rules();

        // Initialize the timestamp
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->storeTimestamp();
    }

    /**
     * Remove permalinks when deactivating the plugin
     *
     * @see    https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     * @return void
     */
    public function deactivate(): void
    {
        // First, unregister the post type, so the rules are no longer in memory.
        $instanceManager = InstanceManagerFacade::getInstance();
        $postTypeObjects = array_map(
            function ($serviceClass) use ($instanceManager) {
                return $instanceManager->getInstance($serviceClass);
            },
            ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\PostTypes')
        );
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->unregisterPostType();
        }

        // Then, clear the permalinks to remove the post type's rules from the database.
        \flush_rewrite_rules();

        // Regenerate the timestamp
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->storeTimestamp();
    }
}

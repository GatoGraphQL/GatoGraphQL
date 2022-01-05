<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait UseTabpanelMenuPageTrait
{
    /**
     * Enqueue the required assets
     */
    protected function enqueueTabpanelAssets(): void
    {
        $mainPluginURL = (string) App::getMainPluginManager()->getConfig('url');
        $mainPluginVersion = (string) App::getMainPluginManager()->getConfig('version');

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-tabpanel',
            $mainPluginURL . 'assets/css/tabpanel.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_script(
            'graphql-api-tabpanel',
            $mainPluginURL . 'assets/js/tabpanel.js',
            array('jquery'),
            $mainPluginVersion
        );
    }
}

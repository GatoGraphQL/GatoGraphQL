<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;

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
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();

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

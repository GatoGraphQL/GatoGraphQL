<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\PluginInfo;

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
        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-tabpanel',
            PluginInfo::get('url') . 'assets/css/tabpanel.css',
            array(),
            PluginInfo::get('version')
        );
        \wp_enqueue_script(
            'graphql-api-tabpanel',
            PluginInfo::get('url') . 'assets/js/tabpanel.js',
            array('jquery'),
            PluginInfo::get('version')
        );
    }
}

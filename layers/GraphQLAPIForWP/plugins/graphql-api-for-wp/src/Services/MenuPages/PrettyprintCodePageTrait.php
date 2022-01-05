<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait PrettyprintCodePageTrait
{
    /**
     * Enqueue the required assets
     */
    protected function enqueuePrettyprintAssets(): void
    {
        $mainPluginURL = (string) App::getMainPluginManager()->getConfig('url');
        $mainPluginVersion = (string) App::getMainPluginManager()->getConfig('version');

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-prettyprint',
            $mainPluginURL . 'assets/css/vendors/code-prettify/desert.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_script(
            'graphql-api-prettyprint',
            $mainPluginURL . 'assets/js/vendors/code-prettify/run_prettify.js',
            array(),
            $mainPluginVersion,
            true
        );
    }
}

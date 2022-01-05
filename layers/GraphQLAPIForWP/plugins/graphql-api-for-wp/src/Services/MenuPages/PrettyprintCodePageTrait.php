<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;

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
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();

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

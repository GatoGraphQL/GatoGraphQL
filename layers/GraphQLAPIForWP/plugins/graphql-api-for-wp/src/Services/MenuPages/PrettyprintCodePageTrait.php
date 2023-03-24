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
    protected function enqueueHighlightJSAssets(): void
    {
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();

        // Commented out Prettify
        // \wp_enqueue_style(
        //     'graphql-api-prettyprint',
        //     $mainPluginURL . 'assets/css/vendors/code-prettify/desert.css',
        //     array(),
        //     $mainPluginVersion
        // );
        // \wp_enqueue_script(
        //     'graphql-api-prettyprint',
        //     $mainPluginURL . 'assets/js/vendors/code-prettify/run_prettify.js',
        //     array(),
        //     $mainPluginVersion,
        //     true
        // );

        /**
         * Using highlight.js
         *
         * @see https://highlightjs.org/usage/
         */
        \wp_enqueue_style(
            'highlight-style',
            $mainPluginURL . 'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_script(
            'highlight',
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/highlight.min.js',
            array(),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'highlight-language-graphql',
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/graphql.min.js',
            array('highlight'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'highlight-language-json',
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/json.min.js',
            array('highlight'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'highlight-language-bash',
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/bash.min.js',
            array('highlight'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'highlight-run',
            $mainPluginURL . 'assets/js/run_highlight.js',
            array('highlight'),
            $mainPluginVersion,
            true
        );
    }
}

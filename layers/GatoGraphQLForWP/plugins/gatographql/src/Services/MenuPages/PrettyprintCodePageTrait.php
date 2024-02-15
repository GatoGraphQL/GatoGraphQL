<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

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
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        // Commented out Prettify
        // \wp_enqueue_style(
        //     'gatographql-prettyprint',
        //     $mainPluginURL . 'assets/css/vendors/code-prettify/desert.css',
        //     array(),
        //     $mainPluginVersion
        // );
        // \wp_enqueue_script(
        //     'gatographql-prettyprint',
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
            'highlight-language-xml',
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/xml.min.js',
            array('highlight'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'highlight-language-diff',
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/diff.min.js',
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

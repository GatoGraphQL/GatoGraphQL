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
     *
     * @param string[]|null $languages
     */
    protected function enqueueHighlightJSAssets(?array $languages = null): void
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
            'highlight-run',
            $mainPluginURL . 'assets/js/run_highlight.js',
            array('highlight'),
            $mainPluginVersion,
            true
        );

        $languageFiles = [
            'graphql' => 'graphql.min.js',
            'json' => 'json.min.js', 
            'bash' => 'bash.min.js',
            'xml' => 'xml.min.js',
            'diff' => 'diff.min.js'
        ];
        foreach ($languageFiles as $language => $file) {
            if ($languages === null || in_array($language, $languages)) {
                \wp_enqueue_script(
                    "highlight-language-{$language}",
                    $mainPluginURL . "assets/js/vendors/highlight-11.6.0/languages/{$file}",
                    array('highlight'),
                    $mainPluginVersion,
                    true
                );
            }
        }
    }
}

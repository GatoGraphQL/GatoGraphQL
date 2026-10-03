<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait PrettyprintCodePageTrait
{
    use EnqueuePluginAssetsTrait;

    /**
     * Enqueue the required assets
     *
     * @param string[]|null $languages
     */
    protected function enqueueHighlightJSAssets(?array $languages = null): void
    {
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
        $this->enqueueMainPluginAssetStyle(
            'highlight-style',
            'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css'
        );
        $this->enqueueMainPluginAssetScript(
            'highlight',
            'assets/js/vendors/highlight-11.6.0/highlight.min.js',
            array(),
            true
        );
        $this->enqueueMainPluginAssetScript(
            'highlight-run',
            'assets/js/run_highlight.js',
            array('highlight'),
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
                $this->enqueueMainPluginAssetScript(
                    "highlight-language-{$language}",
                    "assets/js/vendors/highlight-11.6.0/languages/{$file}",
                    array('highlight'),
                    true
                );
            }
        }
    }
}

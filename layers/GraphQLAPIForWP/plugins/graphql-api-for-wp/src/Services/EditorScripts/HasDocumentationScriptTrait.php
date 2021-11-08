<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\Constants\DocumentationConstants;
use GraphQLAPI\GraphQLAPI\Services\Helpers\LocaleHelper;

/**
 * Add translatable documentation to the script.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/editor-scripts/{scriptName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 */
trait HasDocumentationScriptTrait
{
    abstract protected function getLocaleHelper(): LocaleHelper;

    /**
     * Docs are bundled as chunks by webpack, and loaded lazily
     * The `publicPath` property for `config.output` must be provided
     * pointing to the generated chunks folder, otherwise it is
     * by default resolved as /wp-admin/..., producing a 404.
     *
     * The public path will be set under global variable `__webpack_public_path__` in JS
     *
     * @see https://v4.webpack.js.org/guides/public-path/#on-the-fly
     */
    abstract protected function getScriptPublicPath(): string;

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getDocsLocalizedData(): array
    {
        $data = [];
        // Add the locale language?
        if ($this->addLocalLanguage()) {
            $data[DocumentationConstants::LOCALE_LANG] = $this->getLocaleHelper()->getLocaleLanguage();
        }
        // Add the default language?
        if ($defaultLang = $this->getDefaultLanguage()) {
            $data[DocumentationConstants::DEFAULT_LANG] = $defaultLang;
        }
        $data['publicPath'] = $this->getScriptPublicPath();
        return $data;
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return false;
    }
    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        return null;
    }

    /**
     * In what languages is the documentation available
     *
     * @return string[]
     */
    protected function getDocLanguages(): array
    {
        $langs = [];
        if ($defaultLang = $this->getDefaultLanguage()) {
            $langs[] = $defaultLang;
        }
        return $langs;
    }

    /**
     * Register the documentation (from under folder "docs/"), for the locale and the default language
     *
     * @param string[] $dependencies
     */
    protected function registerDocumentationScripts(
        string $scriptName,
        string $url,
        array $dependencies = [],
        string $version = ''
    ): void {
        if ($defaultLang = $this->getDefaultLanguage()) {
            \wp_register_script(
                $scriptName . '-' . $defaultLang,
                $url . 'build/docs-' . $defaultLang . '.js',
                $dependencies,
                $version,
                true
            );
            \wp_enqueue_script($scriptName . '-' . $defaultLang);
        }
        if ($this->addLocalLanguage()) {
            $localeLang = $this->getLocaleHelper()->getLocaleLanguage();
            // Check the current locale has been translated, otherwise if will try to load an unexisting file
            // If the locale lang is the same as the default lang, the file has already been loaded
            if ($localeLang != $defaultLang && in_array($localeLang, $this->getDocLanguages())) {
                \wp_register_script(
                    $scriptName . '-' . $localeLang,
                    $url . 'build/docs-' . $localeLang . '.js',
                    $dependencies,
                    $version,
                    true
                );
                \wp_enqueue_script($scriptName . '-' . $localeLang);
            }
        }
    }
}

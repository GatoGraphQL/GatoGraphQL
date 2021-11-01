<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EditorScripts;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\LocaleHelper;
use GraphQLAPI\GraphQLAPI\Services\Scripts\AbstractScript;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Base class for a Gutenberg script.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/editor-scripts/{scriptName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 */
abstract class AbstractEditorScript extends AbstractScript
{
    use HasDocumentationScriptTrait;

    private ?EditorHelpers $editorHelpers = null;
    private ?LocaleHelper $localeHelper = null;

    final public function setEditorHelpers(EditorHelpers $editorHelpers): void
    {
        $this->editorHelpers = $editorHelpers;
    }
    final protected function getEditorHelpers(): EditorHelpers
    {
        return $this->editorHelpers ??= $this->instanceManager->getInstance(EditorHelpers::class);
    }
    final public function setLocaleHelper(LocaleHelper $localeHelper): void
    {
        $this->localeHelper = $localeHelper;
    }
    final protected function getLocaleHelper(): LocaleHelper
    {
        return $this->localeHelper ??= $this->instanceManager->getInstance(LocaleHelper::class);
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            $this->getDocsLocalizedData()
        );
    }

    /**
     * URL to the script
     */
    protected function getScriptDirURL(): string
    {
        return $this->getPluginURL() . '/editor-scripts/' . $this->getScriptName() . '/';
    }

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
    protected function getScriptPublicPath(): string
    {
        return $this->getScriptDirURL() . 'build/';
    }

    /**
     * Where is the script stored
     */
    protected function getScriptDir(): string
    {
        return $this->getPluginDir() . '/editor-scripts/' . $this->getScriptName();
    }

    /**
     * Post types for which to register the script
     *
     * @return string[]
     */
    protected function getAllowedPostTypes(): array
    {
        return [];
    }

    /**
     * Registers all script assets
     */
    public function initScript(): void
    {
        /**
         * Maybe only load the script when creating/editing for some CPT only
         */
        if (\is_admin()) {
            if ($postTypes = $this->getAllowedPostTypes()) {
                if (!in_array($this->getEditorHelpers()->getEditingPostType(), $postTypes)) {
                    return;
                }
            }
        }

        parent::initScript();

        /**
         * Register the documentation (from under folder "docs/"), for the locale and the default language
         * @todo Maybe uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
         * @see https://github.com/GraphQLAPI/graphql-api-for-wp/issues/1
         */
        // $this->initDocumentationScripts();
    }

    /**
     * Register the documentation (from under folder "docs/"), for the locale and the default language
     */
    protected function initDocumentationScripts(): void
    {
        $dir = $this->getScriptDir();
        $scriptName = $this->getScriptName();
        $script_asset_path = "$dir/build/index.asset.php";
        $url = $this->getScriptDirURL();
        $script_asset = require($script_asset_path);

        $this->registerDocumentationScripts($scriptName, $url, $script_asset['dependencies'], $script_asset['version']);
    }
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use Error;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EditorScripts\HasDocumentationScriptTrait;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Base class for a Gutenberg block, within a multi-block plugin.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/blocks/{blockName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 * (this package provides the scaffolding for a single-block plugin,
 * so the plugin .php file is ignored registering a single block is ignored, and everything else is used)
 */
abstract class AbstractBlock extends AbstractAutomaticallyInstantiatedService
{
    use HasDocumentationScriptTrait;

    protected ModuleRegistryInterface $moduleRegistry;
    protected UserAuthorizationInterface $userAuthorization;

    function __construct(
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization
    ) {
        $this->moduleRegistry = $moduleRegistry;
        $this->userAuthorization = $userAuthorization;
    }

    /**
     * Execute this function to initialize the block
     *
     * @return void
     */
    final public function initialize(): void
    {
        \add_action('init', [$this, 'initBlock']);
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->moduleRegistry->isModuleEnabled($enablingModule);
        }
        return parent::isServiceEnabled();
    }

    /**
     * Plugin dir
     *
     * @return string
     */
    abstract protected function getPluginDir(): string;
    /**
     * Plugin URL
     *
     * @return string
     */
    abstract protected function getPluginURL(): string;
    /**
     * Block namespace
     *
     * @return string
     */
    abstract protected function getBlockNamespace(): string;
    /**
     * Block name
     *
     * @return string
     */
    abstract protected function getBlockName(): string;

    /**
     * If the block is dynamic, it will return the server-side HTML through function `renderBlock`
     */
    protected function isDynamicBlock(): bool
    {
        return false;
    }
    /**
     * Produce the HTML for dynamic blocks
     *
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        return '';
    }
    /**
     * Do not output the content, and show an error message to the visitor
     */
    public function renderUnauthorizedAccess(): string
    {
        return sprintf(
            '<p>%s</p>',
            \__('You are not authorized to see this content', 'graphql-api')
        );
    }
    /**
     * Register index.css
     *
     * @return boolean
     */
    protected function registerEditorCSS(): bool
    {
        return false;
    }
    /**
     * Register style-index.css
     *
     * @return boolean
     */
    protected function registerCommonStyleCSS(): bool
    {
        return false;
    }
    /**
     * The block full name: namespace/blockName
     *
     * @return string
     */
    final public function getBlockFullName(): string
    {
        return sprintf(
            '%s/%s',
            $this->getBlockNamespace(),
            $this->getBlockName()
        );
    }
    /**
     * Block registration name: namespace-blockName
     *
     * @return string
     */
    final protected function getBlockRegistrationName(): string
    {
        return sprintf(
            '%s-%s',
            $this->getBlockNamespace(),
            $this->getBlockName()
        );
    }
    /**
     * Block registration name: namespace-blockName
     *
     * @return string
     */
    final protected function getBlockLocalizationName(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GeneralUtils */
        $generalUtils = $instanceManager->getInstance(GeneralUtils::class);
        return $generalUtils->dashesToCamelCase($this->getBlockRegistrationName());
    }
    /**
     * Block class name: wp-block-namespace-blockName
     *
     * @return string
     */
    protected function getBlockClassName(): string
    {
        return sprintf(
            'wp-block-%s',
            $this->getBlockRegistrationName()
        );
    }

    /**
     * Block align class
     */
    public function getAlignClass(): string
    {
        return 'aligncenter';
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getLocalizedData(): array
    {
        return $this->getDocsLocalizedData();
    }

    /**
     * Where is the block stored
     *
     * @return string
     */
    protected function getBlockDirURL(): string
    {
        return $this->getPluginURL() . '/blocks/' . $this->getBlockName() . '/';
    }

    /**
     * Where is the block stored
     *
     * @return string
     */
    protected function getBlockDir(): string
    {
        return $this->getPluginDir() . '/blocks/' . $this->getBlockName();
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return null;
    }

    /**
     * Post types for which to register the script
     *
     * @return string[]
     */
    protected function getAllowedPostTypes(): array
    {
        if ($blockCategory = $this->getBlockCategory()) {
            return $blockCategory->getPostTypes();
        }
        return [];
    }

    /**
     * Dependencies to load before the block
     *
     * @return string[]
     */
    protected function getBlockDependencies(): array
    {
        return [];
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
        return $this->getBlockDirURL() . 'build/';
    }

    /**
     * Registers all block assets so that they can be enqueued through the block editor
     * in the corresponding context.
     *
     * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
     */
    public function initBlock(): void
    {
        /**
         * In the admin, if the block belongs to a category, and the category works only under certain CPTs,
         * then register the block only if we are on any of those CPTs.
         * Otherwise, the block would be registered but the category is not,
         * printing error console such as:
         * > The block "graphql-api/schema-configuration" must have a registered category.
         */
        if (\is_admin()) {
            if ($postTypes = $this->getAllowedPostTypes()) {
                $instanceManager = InstanceManagerFacade::getInstance();
                /** @var EditorHelpers */
                $editorHelpers = $instanceManager->getInstance(EditorHelpers::class);
                if (!in_array($editorHelpers->getEditingPostType(), $postTypes)) {
                    return;
                }
            }
        }

        $dir = $this->getBlockDir();
        $blockFullName = $this->getBlockFullName();

        $script_asset_path = "$dir/build/index.asset.php";
        if (!file_exists($script_asset_path)) {
            throw new Error(
                sprintf(
                    \__('You need to run `npm start` or `npm run build` for the "%s" block first.', 'graphql-api'),
                    $blockFullName
                )
            );
        }

        $url = $this->getBlockDirURL();
        $blockRegistrationName = $this->getBlockRegistrationName();
        $blockConfiguration = [];

        // Load the block scripts and styles
        $index_js     = 'build/index.js';
        $script_asset = require($script_asset_path);
        $scriptRegistrationName = $blockRegistrationName . '-block-editor';
        \wp_register_script(
            $scriptRegistrationName,
            $url . $index_js,
            array_merge(
                $script_asset['dependencies'],
                $this->getBlockDependencies()
            ),
            $script_asset['version']
        );
        $blockConfiguration['editor_script'] = $blockRegistrationName . '-block-editor';

        /**
         * Register editor CSS file
         */
        if ($this->registerEditorCSS()) {
            $editor_css = 'build/index.css';
            \wp_register_style(
                $blockRegistrationName . '-block-editor',
                $url . $editor_css,
                array(),
                // Cast object so PHPStan doesn't throw error
                (string)filemtime("$dir/$editor_css")
            );
            $blockConfiguration['editor_style'] = $blockRegistrationName . '-block-editor';
        }

        /**
         * Register client/editor CSS file
         */
        if ($this->registerCommonStyleCSS()) {
            $style_css = 'build/style-index.css';
            \wp_register_style(
                $blockRegistrationName . '-block',
                $url . $style_css,
                array(),
                // Cast object so PHPStan doesn't throw error
                (string)filemtime("$dir/$style_css")
            );
            $blockConfiguration['style'] = $blockRegistrationName . '-block';
        }

        /**
         * Register callback function for dynamic block
         */
        if ($this->isDynamicBlock()) {
            /**
             * Show only if the user has the right permission
             */
            if ($this->userAuthorization->canAccessSchemaEditor()) {
                $blockConfiguration['render_callback'] = [$this, 'renderBlock'];
            } else {
                $blockConfiguration['render_callback'] = [$this, 'renderUnauthorizedAccess'];
            }
        }

        /**
         * Localize the script with custom data
         * Execute on hook "wp_print_scripts" and not now,
         * because `getLocalizedData` might call EndpointHelpers->getAdminGraphQLEndpoint(),
         * which calls ComponentModelComponentConfiguration::namespaceTypesAndInterfaces(),
         * which is initialized during "wp"
         */
        \add_action('wp_print_scripts', function () use ($scriptRegistrationName) {
            if ($localizedData = $this->getLocalizedData()) {
                \wp_localize_script(
                    $scriptRegistrationName,
                    $this->getBlockLocalizationName(),
                    $localizedData
                );
            }
        });

        \register_block_type($blockFullName, $blockConfiguration);

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
        $dir = $this->getBlockDir();
        $script_asset_path = "$dir/build/index.asset.php";
        $url = $this->getBlockDirURL();
        $script_asset = require($script_asset_path);
        $blockRegistrationName = $this->getBlockRegistrationName();
        $scriptRegistrationName = $blockRegistrationName . '-block-editor';

        $this->registerDocumentationScripts($scriptRegistrationName, $url, $script_asset['dependencies'], $script_asset['version']);
    }
}

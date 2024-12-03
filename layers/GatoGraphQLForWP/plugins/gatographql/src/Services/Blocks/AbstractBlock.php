<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use Error;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\EditorScripts\HasDocumentationScriptTrait;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EditorHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\LocaleHelper;
use GatoGraphQL\GatoGraphQL\Services\Helpers\RenderingHelpers;
use GatoGraphQL\PluginUtils\Services\Helpers\StringConversion;
use PoP\Root\Constants\HookNames;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

use function add_action;
use function is_admin;
use function is_singular;
use function register_block_type;
use function wp_localize_script;
use function wp_enqueue_style;
use function wp_register_script;
use function wp_register_style;

/**
 * Base class for a Gutenberg block, within a multi-block plugin.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/blocks/{blockName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 * (this package provides the scaffolding for a single-block plugin,
 * so the plugin .php file is ignored registering a single block is ignored, and everything else is used)
 */
abstract class AbstractBlock extends AbstractAutomaticallyInstantiatedService implements BlockInterface
{
    use HasDocumentationScriptTrait;
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?StringConversion $stringConversion = null;
    private ?EditorHelpers $editorHelpers = null;
    private ?LocaleHelper $localeHelper = null;
    private ?RenderingHelpers $renderingHelpers = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }
    final protected function getStringConversion(): StringConversion
    {
        if ($this->stringConversion === null) {
            /** @var StringConversion */
            $stringConversion = $this->instanceManager->getInstance(StringConversion::class);
            $this->stringConversion = $stringConversion;
        }
        return $this->stringConversion;
    }
    final protected function getEditorHelpers(): EditorHelpers
    {
        if ($this->editorHelpers === null) {
            /** @var EditorHelpers */
            $editorHelpers = $this->instanceManager->getInstance(EditorHelpers::class);
            $this->editorHelpers = $editorHelpers;
        }
        return $this->editorHelpers;
    }
    final protected function getLocaleHelper(): LocaleHelper
    {
        if ($this->localeHelper === null) {
            /** @var LocaleHelper */
            $localeHelper = $this->instanceManager->getInstance(LocaleHelper::class);
            $this->localeHelper = $localeHelper;
        }
        return $this->localeHelper;
    }
    final protected function getRenderingHelpers(): RenderingHelpers
    {
        if ($this->renderingHelpers === null) {
            /** @var RenderingHelpers */
            $renderingHelpers = $this->instanceManager->getInstance(RenderingHelpers::class);
            $this->renderingHelpers = $renderingHelpers;
        }
        return $this->renderingHelpers;
    }

    /**
     * Execute this function to initialize the block
     */
    final public function initialize(): void
    {
        /**
         * Call it on "boot" after the WP_Query is parsed, so the single CPT
         * is loaded, and asking for `is_singular(CPT)` works.
         */
        App::addAction(
            HookNames::AFTER_BOOT_APPLICATION,
            $this->initBlock(...),
            $this->getPriority()
        );
    }

    /**
     * They must be initialized after the corresponding CPTs!!!!
     * Otherwise they don't get initialized when editing that
     * CPT in the WordPress editor.
     */
    protected function getPriority(): int
    {
        return 10000;
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
        /**
         * Maybe do not initialize for the Internal AppThread
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            !$moduleConfiguration->useSchemaConfigurationInInternalGraphQLServer()
            && AppHelpers::isInternalGraphQLServerAppThread()
        ) {
            return false;
        }

        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule);
        }
        return parent::isServiceEnabled();
    }

    /**
     * Plugin dir
     */
    abstract protected function getPluginDir(): string;
    /**
     * Plugin URL
     */
    abstract protected function getPluginURL(): string;
    /**
     * Block namespace
     */
    abstract protected function getBlockNamespace(): string;
    /**
     * Block name
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
     * @param array<string,mixed> $attributes
     */
    abstract public function renderBlock(array $attributes, string $content): string;

    /**
     * Register index.css
     */
    protected function registerEditorCSS(): bool
    {
        return false;
    }
    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return false;
    }

    protected function registerHighlightJSCSS(): bool
    {
        return true;
    }

    /**
     * The block full name: namespace/blockName
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
     */
    final protected function getBlockLocalizationName(): string
    {
        return $this->getStringConversion()->dashesToCamelCase($this->getBlockRegistrationName());
    }
    /**
     * Block class name: wp-block-namespace-blockName
     */
    protected function getBlockClassName(): string
    {
        return sprintf(
            'wp-block-%s',
            $this->getBlockRegistrationName()
        );
    }

    /**
     * Block align class name
     */
    public function getAlignClassName(): string
    {
        return '';
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getLocalizedData(): array
    {
        return $this->getDocsLocalizedData();
    }

    /**
     * Where is the block stored
     */
    protected function getBlockDirURL(): string
    {
        return $this->getPluginURL() . '/' . $this->getBlocksFolder() . '/' . $this->getBlockName() . '/';
    }

    /**
     * Folder storing all the blocks
     */
    protected function getBlocksFolder(): string
    {
        return 'blocks';
    }

    /**
     * Where is the block stored
     */
    protected function getBlockDir(): string
    {
        return $this->getPluginDir() . '/' . $this->getBlocksFolder() . '/' . $this->getBlockName();
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
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
            return $blockCategory->getCustomPostTypes();
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
     * --------------------------------------------------------------------------------
     *
     * Only register the blocks when editing/viewing the corresponding CPTs,
     * to enable standalone plugins to be installed alongside Gato GraphQL,
     * and avoid a conflict from the same block registered twice.
     *
     * For instance, running Gato GraphQL and Gato Multilingual for Polylang
     * would print error on screen:
     *
     *    Notice: Function WP_Block_Type_Registry::register was called incorrectly. Block type "gatographql-pro/graphiql" is already registered. Please see Debugging in WordPress for more information. (This message was added in version 5.0.0.) in /Users/leo/Local Sites/playground/app/public/wp-includes/functions.php on line 6114
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
         * > The block "gatographql/schema-configuration" must have a registered category.
         */
        $allowedCustomPostTypes = $this->getAllowedPostTypes();
        $isAdmin = is_admin();
        if ($allowedCustomPostTypes === [] && ($isAdmin || $this->loadClientScriptsInCorrespondingSingleCPTsOnly())) {
            return;
        }

        /**
         * Check that we're either eding the post in the wp-admin,
         * or viewing the post in the frontend
         */
        if (
            ($isAdmin && !in_array($this->getEditorHelpers()->getEditingCustomPostType(), $allowedCustomPostTypes))
            || (!$isAdmin && !is_singular($allowedCustomPostTypes))
        ) {
            return;
        }

        if ($isAdmin) {
            /**
             * Register Highlight.js CSS file for documentation
             */
            if ($this->registerHighlightJSCSS()) {
                $mainPlugin = PluginApp::getMainPlugin();
                $mainPluginURL = $mainPlugin->getPluginURL();
                $mainPluginVersion = $mainPlugin->getPluginVersion();
                wp_enqueue_style(
                    'highlight-style',
                    $mainPluginURL . 'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css',
                    array(),
                    $mainPluginVersion
                );
            }
        }

        $dir = $this->getBlockDir();
        $blockFullName = $this->getBlockFullName();

        $script_asset_path = "$dir/build/index.asset.php";
        if (!file_exists($script_asset_path)) {
            throw new Error(
                sprintf(
                    \__('You need to run `npm start` or `npm run build` for the "%s" block first.', 'gatographql'),
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
        wp_register_script(
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
            /** @var string */
            $modificationTime = filemtime("$dir/$editor_css");
            wp_register_style(
                $blockRegistrationName . '-block-editor',
                $url . $editor_css,
                array(),
                $modificationTime
            );
            $blockConfiguration['editor_style'] = $blockRegistrationName . '-block-editor';
        }

        /**
         * Register client/editor CSS file
         */
        if ($this->registerCommonStyleCSS() && $this->mustLoadClientEditorCommonAssets()) {
            $style_css = 'build/style-index.css';
            /** @var string */
            $modificationTime = filemtime("$dir/$style_css");
            wp_register_style(
                $blockRegistrationName . '-block',
                $url . $style_css,
                array(),
                $modificationTime
            );
            $blockConfiguration['style'] = $blockRegistrationName . '-block';
        }

        /**
         * Localize the script with custom data
         * Execute on hook "wp_print_scripts" and not now,
         * because `getLocalizedData` might call EndpointHelpers->getAdminGraphQLEndpoint(),
         * which calls ComponentModelModuleConfiguration::mustNamespaceTypes(),
         * which is initialized during "wp"
         */
        add_action('wp_print_scripts', function () use ($scriptRegistrationName): void {
            if ($localizedData = $this->getLocalizedData()) {
                wp_localize_script(
                    $scriptRegistrationName,
                    $this->getBlockLocalizationName(),
                    $localizedData
                );
            }
        });

        /**
         * Register callback function for dynamic block
         */
        if ($this->isDynamicBlock()) {
            /**
             * Show only if the user has the right permission
             */
            if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
                $blockConfiguration['render_callback'] = $this->renderBlock(...);
            } else {
                $blockConfiguration['render_callback'] = $this->getRenderingHelpers()->getUnauthorizedAccessHTMLMessage(...);
            }
        }

        if ($this->registerBlockServerSide()) {
            register_block_type($blockFullName, $blockConfiguration);
        }

        /**
         * Register the documentation (from under folder "docs/"), for the locale and the default language
         * @todo Maybe uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
         * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/254
         */
        // $this->initDocumentationScripts();
    }

    /**
     * Indicate if to load the client/editor common styles/scripts.
     * That's when either:
     *
     * - In the wp-admin
     * - Loading a single post in the frontend for the CPTs associated with the block
     */
    final protected function mustLoadClientEditorCommonAssets(): bool
    {
        if (is_admin()) {
            return true;
        }

        if (!$this->loadClientScriptsInCorrespondingSingleCPTsOnly()) {
            return true;
        }

        $allowedCustomPostTypes = $this->getAllowedPostTypes();
        if ($allowedCustomPostTypes === []) {
            return true;
        }

        return is_singular($allowedCustomPostTypes);
    }

    protected function loadClientScriptsInCorrespondingSingleCPTsOnly(): bool
    {
        return true;
    }

    /**
     * Allow to not register the block on the server-side
     * for testing purposes.
     */
    protected function registerBlockServerSide(): bool
    {
        return true;
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

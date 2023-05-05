<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Scripts;

use Error;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\PluginUtils\Services\Helpers\StringConversion;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

/**
 * Base class for a Gutenberg script.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/scripts/{scriptName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 */
abstract class AbstractScript extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?StringConversion $stringConversion = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setStringConversion(StringConversion $stringConversion): void
    {
        $this->stringConversion = $stringConversion;
    }
    final protected function getStringConversion(): StringConversion
    {
        /** @var StringConversion */
        return $this->stringConversion ??= $this->instanceManager->getInstance(StringConversion::class);
    }

    /**
     * Execute this function to initialize the script
     */
    final public function initialize(): void
    {
        \add_action('init', $this->initScript(...));
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
         * Only initialize once, for the main AppThread
         */
        if (!AppHelpers::isMainAppThread()) {
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
     * Block name
     */
    abstract protected function getScriptName(): string;

    /**
     * Register CSS
     */
    protected function registerScriptCSS(): bool
    {
        return false;
    }

    /**
     * Register Style Index CSS
     */
    protected function registerStyleIndexCSS(): bool
    {
        return false;
    }

    /**
     * Script localization name
     */
    final protected function getScriptLocalizationName(): string
    {
        return $this->getStringConversion()->dashesToCamelCase($this->getScriptName());
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getLocalizedData(): array
    {
        return [];
    }

    /**
     * URL to the script
     */
    protected function getScriptDirURL(): string
    {
        return $this->getPluginURL() . '/scripts/' . $this->getScriptName() . '/';
    }

    /**
     * Where is the script stored
     */
    protected function getScriptDir(): string
    {
        return $this->getPluginDir() . '/scripts/' . $this->getScriptName();
    }

    /**
     * Dependencies to load before the script
     *
     * @return string[]
     */
    protected function getScriptDependencies(): array
    {
        return [];
    }

    /**
     * Dependencies to load before the style
     *
     * @return string[]
     */
    protected function getStyleDependencies(): array
    {
        return [];
    }

    /**
     * Dependencies to load before the style
     *
     * @return string[]
     */
    protected function getStyleIndexDependencies(): array
    {
        return [];
    }

    /**
     * Registers all script assets
     */
    public function initScript(): void
    {
        $dir = $this->getScriptDir();
        $scriptName = $this->getScriptName();

        $script_asset_path = "$dir/build/index.asset.php";
        if (!file_exists($script_asset_path)) {
            throw new Error(
                sprintf(
                    \__('You need to run `npm start` or `npm run build` for the "%s" script first.', 'gato-graphql'),
                    $scriptName
                )
            );
        }

        $url = $this->getScriptDirURL();

        // Load the block scripts and styles
        $index_js     = 'build/index.js';
        $script_asset = require($script_asset_path);
        \wp_register_script(
            $scriptName,
            $url . $index_js,
            array_merge(
                $script_asset['dependencies'],
                $this->getScriptDependencies()
            ),
            $script_asset['version'],
            true
        );
        \wp_enqueue_script($scriptName);

        /**
         * Register CSS file
         */
        if ($this->registerScriptCSS()) {
            $style_css = 'build/style.css';
            /** @var string */
            $modificationTime = filemtime("$dir/$style_css");
            \wp_register_style(
                $scriptName,
                $url . $style_css,
                $this->getStyleDependencies(),
                $modificationTime
            );
            \wp_enqueue_style($scriptName);
        }

        /**
         * Register Style Index CSS file
         */
        if ($this->registerStyleIndexCSS()) {
            $style_index_css = 'build/style-index.css';
            /** @var string */
            $modificationTime = filemtime("$dir/$style_index_css");
            \wp_register_style(
                $scriptName . 'style-index',
                $url . $style_index_css,
                $this->getStyleIndexDependencies(),
                $modificationTime
            );
            \wp_enqueue_style($scriptName . 'style-index');
        }

        /**
         * Localize the script with custom data
         * Execute on hook "wp_print_scripts" and not now,
         * because `getLocalizedData` might call EndpointHelpers->getAdminGraphQLEndpoint(),
         * which calls ScriptModelScriptConfiguration::mustNamespaceTypes(),
         * which is initialized during "wp"
         */
        \add_action('wp_print_scripts', function () use ($scriptName): void {
            if ($localizedData = $this->getLocalizedData()) {
                \wp_localize_script(
                    $scriptName,
                    $this->getScriptLocalizationName(),
                    $localizedData
                );
            }
        });
    }
}

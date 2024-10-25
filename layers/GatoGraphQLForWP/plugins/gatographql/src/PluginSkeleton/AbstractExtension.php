<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;

/**
 * This class is hosted within the gatographql plugin, and not
 * within the extension plugin. That means that the main plugin
 * must be installed, for any extension to work.
 *
 * This class doesn't have an `activate` function, because `activate`
 * can't be executed within "plugins_loaded", on which we find out if the
 * main plugin is installed and activated.
 *
 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#more-information
 *
 * Then, the activation is done when registering the extension.
 *
 * @see https://github.com/GatoGraphQL/GatoGraphQL/blob/bccf2f0/layers/GatoGraphQLForWP/plugins/extension-demo/gatographql-extension-demo.php#L73-L77
 */
abstract class AbstractExtension extends AbstractPlugin implements ExtensionInterface
{
    protected ?ExtensionInitializationConfigurationInterface $extensionInitializationConfiguration = null;

    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        ?string $commitHash = null,
        ?string $pluginFolder = null, /** Useful to override by standalone plugins */
        ?string $pluginURL = null, /** Useful to override by standalone plugins */
        ?ExtensionInitializationConfigurationInterface $extensionInitializationConfiguration = null,
    ) {
        parent::__construct(
            $pluginFile,
            $pluginVersion,
            $pluginName,
            $commitHash,
            $pluginFolder,
            $pluginURL,
        );
        $this->extensionInitializationConfiguration = $extensionInitializationConfiguration ?? $this->maybeCreateInitializationConfiguration();
    }

    protected function maybeCreateInitializationConfiguration(): ?ExtensionInitializationConfigurationInterface
    {
        $extensionInitializationConfigurationClass = $this->getExtensionInitializationConfigurationClass();
        if ($extensionInitializationConfigurationClass === null) {
            return null;
        }
        return new $extensionInitializationConfigurationClass($this);
    }

    /**
     * ExtensionInitializationConfiguration class for the Plugin
     *
     * @return class-string<ExtensionInitializationConfigurationInterface>
     */
    protected function getExtensionInitializationConfigurationClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $pluginInitializationConfigurationClass = $classNamespace . '\\' . $this->getExtensionInitializationConfigurationClassName();
        if (!class_exists($pluginInitializationConfigurationClass)) {
            return null;
        }
        /** @var class-string<ExtensionInitializationConfigurationInterface> */
        return $pluginInitializationConfigurationClass;
    }

    /**
     * ExtensionInitializationConfiguration class name for the Extension
     */
    protected function getExtensionInitializationConfigurationClassName(): ?string
    {
        return 'ExtensionInitializationConfiguration';
    }

    /**
     * ExtensionInfo class name for the Extension
     */
    protected function getPluginInfoClassName(): ?string
    {
        return 'ExtensionInfo';
    }

    /**
     * Configure the plugin.
     * This defines hooks to set environment variables,
     * so must be executed before those hooks are triggered for first time
     * (in ModuleConfiguration classes)
     */
    protected function callPluginInitializationConfiguration(): void
    {
        $this->extensionInitializationConfiguration?->initialize();
    }

    /**
     * Add configuration for the Module classes
     *
     * @return array<class-string<ModuleInterface>,mixed> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array
    {
        return $this->extensionInitializationConfiguration?->getModuleClassConfiguration() ?? parent::getModuleClassConfiguration();
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    public function getSchemaModuleClassesToSkip(): array
    {
        return $this->extensionInitializationConfiguration?->getSchemaModuleClassesToSkip() ?? [];
    }

    /**
     * Plugin set-up, executed after Gato GraphQL is loaded,
     * and before it is initialized
     */
    final public function setup(): void
    {
        parent::setup();

        /**
         * Priority 100: before Gato GraphQL is initialized
         */
        \add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                /**
                 * Initialize/configure/boot this extension plugin
                 */
                \add_action(
                    PluginLifecycleHooks::INITIALIZE_EXTENSION,
                    $this->initialize(...),
                    $this->getInitializeExtensionPriority()
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION_COMPONENTS,
                    $this->configureComponents(...)
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION,
                    fn () => $this->configure()
                );
                \add_action(
                    PluginLifecycleHooks::BOOT_EXTENSION,
                    $this->boot(...)
                );

                // Execute the plugin's custom setup, if any
                $this->doSetup();
            },
            PluginLifecyclePriorities::SETUP_EXTENSIONS
        );
    }

    protected function getInitializeExtensionPriority(): int
    {
        return 10;
    }

    /**
     * Plugin set-up
     */
    protected function doSetup(): void
    {
        // Function to override
    }

    public function isCommercial(): bool
    {
        return false;
    }
}

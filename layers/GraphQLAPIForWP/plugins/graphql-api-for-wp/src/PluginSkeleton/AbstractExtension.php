<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use PoP\Root\Helpers\ClassHelpers;

/**
 * This class is hosted within the graphql-api-for-wp plugin, and not
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
 * @see https://github.com/leoloso/PoP/blob/bccf2f0/layers/GraphQLAPIForWP/plugins/extension-demo/graphql-api-extension-demo.php#L73-L77
 */
abstract class AbstractExtension extends AbstractPlugin implements ExtensionInterface
{
    protected ?ExtensionInitializationConfigurationInterface $extensionInitializationConfiguration = null;

    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        ?ExtensionInitializationConfigurationInterface $extensionInitializationConfiguration = null,
    ) {
        parent::__construct(
            $pluginFile,
            $pluginVersion,
            $pluginName,
        );
        $this->extensionInitializationConfiguration = $extensionInitializationConfiguration ?? $this->maybeCreateInitializationConfiguration();
    }

    protected function maybeCreateInitializationConfiguration(): ?ExtensionInitializationConfigurationInterface
    {
        $extensionInitializationConfigurationClass = $this->getExtensionInitializationConfigurationClass();
        if ($extensionInitializationConfigurationClass === null) {
            return null;
        }
        return new $extensionInitializationConfigurationClass();
    }

    /**
     * ExtensionInitializationConfiguration class for the Plugin
     */
    protected function getExtensionInitializationConfigurationClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $pluginInitializationConfigurationClass = $classNamespace . '\\' . $this->getExtensionInitializationConfigurationClassName();
        if (!class_exists($pluginInitializationConfigurationClass)) {
            return null;
        }
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
     * @return array<string, mixed> [key]: Module class, [value]: Configuration
     */
    public function getComponentClassConfiguration(): array
    {
        return $this->extensionInitializationConfiguration?->getComponentClassConfiguration() ?? parent::getComponentClassConfiguration();
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return string[] List of `Module` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array
    {
        return $this->extensionInitializationConfiguration?->getSchemaComponentClassesToSkip() ?? [];
    }

    /**
     * Plugin set-up, executed after the GraphQL API plugin is loaded,
     * and before it is initialized
     */
    final public function setup(): void
    {
        parent::setup();

        /**
         * Priority 100: before the GraphQL API plugin is initialized
         */
        \add_action(
            'plugins_loaded',
            function (): void {
                /**
                 * Initialize/configure/boot this extension plugin
                 */
                \add_action(
                    PluginLifecycleHooks::INITIALIZE_EXTENSION,
                    $this->initialize(...)
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION_COMPONENTS,
                    $this->configureComponents(...)
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION,
                    $this->configure(...)
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

    /**
     * Plugin set-up
     */
    protected function doSetup(): void
    {
        // Function to override
    }
}

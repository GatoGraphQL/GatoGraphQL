<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

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
    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        protected ?AbstractExtensionConfiguration $extensionConfiguration = null,
    ) {
        parent::__construct(
            $pluginFile,
            $pluginVersion,
            $pluginName,
        );
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
     * (in ComponentConfiguration classes)
     */
    protected function callPluginInitializationConfiguration(): void
    {
        $this->extensionConfiguration?->initialize();
    }

    /**
     * Add configuration for the Component classes
     *
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    public function getComponentClassConfiguration(): array
    {
        return $this->extensionConfiguration?->getComponentClassConfiguration() ?? parent::getComponentClassConfiguration();
    }

    /**
     * Add schema Component classes to skip initializing
     *
     * @return string[] List of `Component` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array
    {
        return $this->extensionConfiguration?->getSchemaComponentClassesToSkip() ?? parent::getSchemaComponentClassesToSkip();
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
                    [$this, 'initialize']
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION_COMPONENTS,
                    [$this, 'configureComponents']
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION,
                    [$this, 'configure']
                );
                \add_action(
                    PluginLifecycleHooks::BOOT_EXTENSION,
                    [$this, 'boot']
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

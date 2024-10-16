<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\MainPluginInitializationConfigurationInterface;

/**
 * This class is not used by Gato GraphQL.
 *
 * Instead, it is a convenience class for standalone plugins,
 * to supersede GatoGraphQL\GatoGraphQL\Plugin
 */
abstract class AbstractStandalonePlugin extends Plugin
{
    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        string $commitHash = null, /** Useful for development to regenerate the container when testing the generated plugin */
        ?MainPluginInitializationConfigurationInterface $pluginInitializationConfiguration = null,
    ) {
        $pluginFolder = \dirname($pluginFile) . '/vendor/gatographql/gatographql';
        $pluginURL = \plugin_dir_url($pluginFile) . 'vendor/gatographql/gatographql/';

        parent::__construct(
            $pluginFile,
            $pluginVersion,
            $pluginName,
            $commitHash,
            $pluginFolder,
            $pluginURL,
            $pluginInitializationConfiguration,
        );
    }

    /**
     * @return class-string<PluginInfoInterface>|null
     */
    protected function getPluginInfoClass(): ?string
    {
        return $this->getPluginInfoClassFromPluginClass(Plugin::class);
    }
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractGatoGraphQLBundleExtension;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInitializationConfigurationInterface;

abstract class AbstractGatoGraphQLStandaloneExtensionBundle extends AbstractGatoGraphQLBundleExtension
{
    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        string $commitHash = null, /** Useful for development to regenerate the container when testing the generated plugin */
        ?ExtensionInitializationConfigurationInterface $extensionInitializationConfiguration = null,
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
            $extensionInitializationConfiguration,
        );
    }
}

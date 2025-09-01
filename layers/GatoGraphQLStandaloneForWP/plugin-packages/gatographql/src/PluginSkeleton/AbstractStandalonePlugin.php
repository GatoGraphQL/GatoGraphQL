<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\MainPluginInitializationConfigurationInterface;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginInfoInterface;

/**
 * This class is not used by Gato GraphQL.
 *
 * Instead, it is a convenience class for standalone plugins,
 * to supersede GatoGraphQL\GatoGraphQL\Plugin
 */
abstract class AbstractStandalonePlugin extends Plugin
{
    use StandalonePluginTrait;

    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        ?string $commitHash = null, /** Useful for development to regenerate the container when testing the generated plugin */
        ?MainPluginInitializationConfigurationInterface $pluginInitializationConfiguration = null,
    ) {
        $pluginFolder = \dirname($pluginFile) . '/' . $this->getGatoGraphQLComposerRelativePath();
        $pluginURL = \plugin_dir_url($pluginFile) . $this->getGatoGraphQLComposerRelativePath() . '/';

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

    protected function getModuleClassname(): string
    {
        return 'StandaloneModule';
    }

    protected function getPluginInitializationConfigurationClassname(): string
    {
        return 'StandalonePluginInitializationConfiguration';
    }

    /**
     * Revalidate the license after 7 days
     * (while the license is active)
     */
    protected function getNumberOfDaysToRevalidateCommercialExtensionActivatedLicenses(): ?int
    {
        return 7;
    }
}

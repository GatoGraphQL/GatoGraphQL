<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Assets;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginInterface;
use GatoGraphQL\GatoGraphQL\StaticHelpers\AssetVersionHelpers;

use function wp_enqueue_script;
use function wp_enqueue_style;

/**
 * Enqueue a plugin's own asset by its path within the plugin, from which
 * both its URL and its version are taken.
 */
trait EnqueuePluginAssetsTrait
{
    /**
     * @param string $assetRelativePath The asset's path relative to the plugin's folder
     * @param string[] $dependencies
     */
    protected function enqueuePluginAssetStyle(
        PluginInterface $plugin,
        string $handle,
        string $assetRelativePath,
        array $dependencies = [],
    ): void {
        wp_enqueue_style(
            $handle,
            $plugin->getPluginURL() . $assetRelativePath,
            $dependencies,
            $this->getPluginAssetVersion($plugin, $assetRelativePath)
        );
    }

    /**
     * @param string $assetRelativePath The asset's path relative to the plugin's folder
     * @param string[] $dependencies
     */
    protected function enqueuePluginAssetScript(
        PluginInterface $plugin,
        string $handle,
        string $assetRelativePath,
        array $dependencies = [],
        bool $inFooter = false,
    ): void {
        wp_enqueue_script(
            $handle,
            $plugin->getPluginURL() . $assetRelativePath,
            $dependencies,
            $this->getPluginAssetVersion($plugin, $assetRelativePath),
            $inFooter
        );
    }

    /**
     * @param string $assetRelativePath The asset's path relative to the main plugin's folder
     * @param string[] $dependencies
     */
    protected function enqueueMainPluginAssetStyle(
        string $handle,
        string $assetRelativePath,
        array $dependencies = [],
    ): void {
        $this->enqueuePluginAssetStyle(PluginApp::getMainPlugin(), $handle, $assetRelativePath, $dependencies);
    }

    /**
     * @param string $assetRelativePath The asset's path relative to the main plugin's folder
     * @param string[] $dependencies
     */
    protected function enqueueMainPluginAssetScript(
        string $handle,
        string $assetRelativePath,
        array $dependencies = [],
        bool $inFooter = false,
    ): void {
        $this->enqueuePluginAssetScript(
            PluginApp::getMainPlugin(),
            $handle,
            $assetRelativePath,
            $dependencies,
            $inFooter
        );
    }

    /**
     * @see AssetVersionHelpers::getAssetVersion()
     */
    protected function getPluginAssetVersion(PluginInterface $plugin, string $assetRelativePath): string
    {
        return AssetVersionHelpers::getAssetVersion(
            $plugin->getPluginVersion(),
            $plugin->getPluginDir() . '/' . $assetRelativePath
        );
    }
}

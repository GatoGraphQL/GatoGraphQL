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
     * The look shared by the admin screens of the Gato plugins, drawn
     * inside a wrapper with the classes from `getAdminUIThemeClass()`.
     *
     * @return string The stylesheet's handle
     */
    protected function enqueueAdminUIStyle(): string
    {
        $this->enqueueMainPluginAssetStyle('gatographql-ui', 'assets/css/gato-ui.css');
        return 'gatographql-ui';
    }

    /**
     * The classes of the wrapper of an admin screen drawn with the shared
     * look, themed for the main plugin.
     */
    protected function getAdminUIThemeClass(): string
    {
        return 'gato-ui gato-ui--' . PluginApp::getMainPlugin()->getAdminUIThemeSlug();
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

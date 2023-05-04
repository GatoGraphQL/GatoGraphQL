<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\ExternalDependencyWrappers\Composer\Semver\SemverWrapper;
use GraphQLAPI\GraphQLAPI\Exception\ExtensionNotRegisteredException;
use GraphQLAPI\GraphQLAPI\PluginApp;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;

class ExtensionManager extends AbstractPluginManager
{
    /** @var string[] */
    private array $inactiveExtensionDependedUponPluginFiles = [];

    /**
     * Have the extensions organized by their class
     *
     * @var array<class-string<ExtensionInterface>,ExtensionInterface>
     */
    private array $extensionClassInstances = [];

    /**
     * Have the extensions organized by their baseName
     *
     * @var array<string,ExtensionInterface>
     */
    private array $extensionBaseNameInstances = [];

    /**
     * Extensions, organized under their name.
     *
     * @return array<string,ExtensionInterface>
     */
    public function getExtensions(): array
    {
        return $this->extensionBaseNameInstances;
    }

    /**
     * @param class-string<ExtensionInterface> $extensionClass
     */
    public function getExtension(string $extensionClass): ExtensionInterface
    {
        if (!isset($this->extensionClassInstances[$extensionClass])) {
            throw new ExtensionNotRegisteredException(
                sprintf(
                    \__('The extension with class \'%s\' has not been registered yet', 'graphql-api'),
                    $extensionClass
                )
            );
        }
        return $this->extensionClassInstances[$extensionClass];
    }

    public function register(ExtensionInterface $extension): ExtensionInterface
    {
        $extensionClass = get_class($extension);
        $this->extensionClassInstances[$extensionClass] = $extension;
        $this->extensionBaseNameInstances[$extension->getPluginBaseName()] = $extension;
        return $extension;
    }

    /**
     * Validate that the extension can be registered:
     *
     * 1. It hasn't been registered yet (eg: the plugin is not duplicated)
     * 2. The required version of the main plugin is the one installed
     *
     * If the assertion fails, it prints an error on the WP admin and returns false
     *
     * @param string|null $mainPluginVersionConstraint the semver version constraint required for the plugin (eg: "^1.0" means >=1.0.0 and <2.0.0)
     * @return bool `true` if the extension can be registered, `false` otherwise
     *
     * @see https://getcomposer.org/doc/articles/versions.md#versions-and-constraints
     */
    public function assertIsValid(
        string $extensionClass,
        string $extensionVersion,
        ?string $extensionName = null,
        ?string $mainPluginVersionConstraint = null,
    ): bool {
        // Validate that the extension is not registered yet.
        if (isset($this->extensionClassInstances[$extensionClass])) {
            $this->printAdminNoticeErrorMessage(
                sprintf(
                    __('Extension <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                    $extensionName ?? $this->extensionClassInstances[$extensionClass]->getPluginName(),
                    $this->extensionClassInstances[$extensionClass]->getPluginVersion(),
                    $extensionVersion,
                )
            );
            return false;
        }

        /**
         * Validate that the required version of the GraphQL API for WP plugin is installed.
         */
        $mainPlugin = PluginApp::getMainPluginManager()->getPlugin();
        $mainPluginVersion = $mainPlugin->getPluginVersion();
        if (
            $mainPluginVersionConstraint !== null && !SemverWrapper::satisfies(
                $mainPluginVersion,
                $mainPluginVersionConstraint
            )
        ) {
            $this->printAdminNoticeErrorMessage(
                sprintf(
                    __('Extension <strong>%s</strong> requires plugin <strong>%s</strong> to satisfy version constraint <code>%s</code>, but the current version <code>%s</code> does not. The extension has not been loaded.', 'graphql-api'),
                    $extensionName ?? $extensionClass,
                    $mainPlugin->getPluginName(),
                    $mainPluginVersionConstraint,
                    $mainPlugin->getPluginVersion(),
                )
            );
            return false;
        }

        return true;
    }

    /**
     * Register the depended-upon plugin main file(s), so that
     * when the plugin is activated, the container is regenerated
     *
     * @param string[] $inactiveExtensionDependedUponPluginFiles
     */
    public function registerInactiveExtensionDependedUponPluginFiles(array $inactiveExtensionDependedUponPluginFiles): void
    {
        $this->inactiveExtensionDependedUponPluginFiles = array_merge(
            $this->inactiveExtensionDependedUponPluginFiles,
            $inactiveExtensionDependedUponPluginFiles
        );
    }

    /**
     * Plugin main files that need to be activated in order
     * for some GraphQL API extension to become active.
     *
     * @return string[]
     */
    public function getInactiveExtensionsDependedUponPluginFiles(): array
    {
        return $this->inactiveExtensionDependedUponPluginFiles;
    }
}

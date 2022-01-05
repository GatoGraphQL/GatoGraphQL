<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use Exception;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPlugin;

class MainPluginManager extends AbstractPluginManager
{
    private ?AbstractMainPlugin $mainPlugin = null;

    public function register(AbstractMainPlugin $mainPlugin): AbstractMainPlugin
    {
        $this->mainPlugin = $mainPlugin;
        return $mainPlugin;
    }

    /**
     * Validate that the plugin is not registered yet.
     * If it is, print an error and return false
     */
    public function assertIsValid(
        string $pluginVersion
    ): bool {
        if ($this->mainPlugin !== null) {
            $this->printAdminNoticeErrorMessage(
                sprintf(
                    __('Plugin <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                    $this->mainPlugin->getConfig('name'),
                    $this->mainPlugin->getConfig('version'),
                    $pluginVersion,
                )
            );
            return false;
        }

        return true;
    }

    /**
     * Get the configuration for the main plugin
     *
     * @return array<string, mixed>
     */
    protected function getFullConfiguration(): array
    {
        if ($this->mainPlugin === null) {
            throw new Exception(
                __('The main plugin has not been registered yet', 'graphql-api')
            );
        }
        return $this->mainPlugin->getFullConfiguration();
    }

    /**
     * Get a configuration value for the main plugin
     */
    public function getConfig(string $key): mixed
    {
        $mainPluginConfig = $this->getFullConfiguration();
        return $mainPluginConfig[$key];
    }
}

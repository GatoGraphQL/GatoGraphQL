<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginManagement;

use GatoGraphQL\GatoGraphQL\Exception\MainPluginNotRegisteredException;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\MainPluginInterface;

class MainPluginManager extends AbstractPluginManager
{
    private ?MainPluginInterface $mainPlugin = null;

    public function register(MainPluginInterface $mainPlugin): MainPluginInterface
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
                    __('Plugin <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'gato-graphql'),
                    $this->mainPlugin->getPluginName(),
                    $this->mainPlugin->getPluginVersion(),
                    $pluginVersion,
                )
            );
            return false;
        }

        return true;
    }

    public function getPlugin(): MainPluginInterface
    {
        if ($this->mainPlugin === null) {
            throw new MainPluginNotRegisteredException(
                __('The main plugin has not been registered yet', 'gato-graphql')
            );
        }
        return $this->mainPlugin;
    }
}

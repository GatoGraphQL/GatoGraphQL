<?php
namespace PoPCMSSchema\UserState\Settings;

class SettingsManager
{
    public function __construct()
    {
        SettingsManagerFactory::setInstance($this);
    }

    public function requiresUserState($route)
    {
        $processor = SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $requiresUserState = $processor->requiresUserState();
        if (is_array($requiresUserState)) {
            return $requiresUserState[$route];
        }

        return $requiresUserState;
    }
}

/**
 * Initialization
 */
new SettingsManager();

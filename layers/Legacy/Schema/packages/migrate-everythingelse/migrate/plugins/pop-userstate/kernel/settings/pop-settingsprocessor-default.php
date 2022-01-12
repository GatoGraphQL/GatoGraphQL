<?php
namespace PoPSchema\UserState\Settings;

use PoP\ComponentModel\State\ApplicationState;

abstract class DefaultSettingsProcessorBase extends SettingsProcessorBase
{
    public function init()
    {
        parent::init();

        SettingsProcessorManagerFactory::getInstance()->setDefault($this);
    }

    public function requiresUserState()
    {
        $route = \PoP\Root\App::getState('route');

        // Check if the page has checkpoints. If so, assume it requires user state
        if ($checkpoints = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance()->getCheckpoints($route)) {
            return !empty($checkpoints);
        }

        return parent::requiresUserState();
    }
}

<?php
namespace PoPCMSSchema\UserState\Settings;

use PoP\ComponentModel\State\ApplicationState;

abstract class DefaultSettingsProcessorBase extends SettingsProcessorBase
{
    public function init()
    {
        parent::init();

        SettingsProcessorManagerFactory::getInstance()->setDefault($this);
    }

    public function requiresUserState(): bool
    {
        $route = \PoP\Root\App::getState('route');

        // Check if the page has checkpoints. If so, assume it requires user state
        $checkpoints = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance()->getRouteCheckpoints($route)) {
        return !empty($checkpoints);
    }
}

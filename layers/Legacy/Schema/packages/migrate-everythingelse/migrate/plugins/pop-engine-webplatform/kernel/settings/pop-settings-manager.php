<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_SettingsManager extends \PoP\ComponentModel\Settings\SettingsManager
{
    public function silentDocument($route = null)
    {
        $vars = ApplicationState::getVars();
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $silent = $processor->silentDocument();
        if (is_array($silent)) {
            return $silent[$route];
        }

        return $silent;
    }

    public function isMultipleopen($route = null)
    {
        $vars = ApplicationState::getVars();
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $multipleopen = $processor->isMultipleopen();
        if (is_array($multipleopen)) {
            return $multipleopen[$route];
        }

        return $multipleopen;
    }

    public function isAppshell($route = null)
    {
        $vars = ApplicationState::getVars();
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $appshell = $processor->isAppshell();
        if (is_array($appshell)) {
            return $appshell[$route];
        }

        return $appshell;
    }

    public function storeLocal($route = null)
    {
        $vars = ApplicationState::getVars();
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $storelocal = $processor->storeLocal();
        if (is_array($storelocal)) {
            return $storelocal[$route];
        }

        return $storelocal;
    }
}

/**
 * Initialization
 */
new PoP_Module_SettingsManager();

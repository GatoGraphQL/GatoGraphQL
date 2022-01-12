<?php
namespace PoP\ComponentModel\Settings;

use PoP\ComponentModel\State\ApplicationState;

class SettingsManager
{
    public function __construct()
    {
        SettingsManagerFactory::setInstance($this);
    }

    public function getCheckpoints($route = null)
    {
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = SettingsProcessorManagerFactory::getInstance()->getProcessor($route);
        $checkpoints = $processor->getCheckpoints();
        if ($checkpoints[$route]) {
            return $checkpoints[$route];
        }
        return array();
    }

    public function isFunctional($route = null)
    {
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $functional = $processor->isFunctional();
        if (is_array($functional)) {
            return $functional[$route];
        }

        return $functional;
    }

    public function isForInternalUse($route = null)
    {
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }

        $processor = SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $internal = $processor->isForInternalUse();
        if (is_array($internal)) {
            return $internal[$route];
        }

        return $internal;
    }

    public function needsTargetId($route = null)
    {
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }
        
        $processor = SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $targetids = $processor->needsTargetId();
        if (is_array($targetids)) {
            return $targetids[$route];
        }

        return $targetids;
    }

    public function getRedirectUrl($route = null)
    {
        if (!$route) {
            $route = \PoP\Root\App::getState('route');
        }
        
        $processor = SettingsProcessorManagerFactory::getInstance()->getProcessor($route);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $redirect_urls = $processor->getRedirectUrl();
        if (is_array($redirect_urls)) {
            return $redirect_urls[$route];
        }

        return $redirect_urls;
    }
}

/**
 * Initialization
 */
new SettingsManager();

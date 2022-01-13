<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ResourceLoader_EnqueueFileHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter('getEnqueuefileType', array($this, 'getEnqueuefileType'));
    }

    public function getEnqueuefileType($type)
    {

        // All the build pages, do not use 'bundle' or 'bundlegroup', to make sure we're not generating these files on runtime,
        // since those updated files may not exist yet (they are generated through the build tools!) and we don't want to bundle out-of-date or non-existing versions
        if ($this->isForInternalUse()) {
            return 'resource';
        }

        return $type;
    }

    protected function isForInternalUse()
    {
        if (\PoP\Root\App::getState(['routing', 'is-standard'])) {
            $route = \PoP\Root\App::getState('route');
        
            $processor = \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getProcessor($route);
            if ($internals = $processor->isForInternalUse()) {
                return $internals[$route] ?? false;
            }
        }

        return false;
    }
}

/**
 * Initialization
 */
new PoP_ResourceLoader_EnqueueFileHooks();

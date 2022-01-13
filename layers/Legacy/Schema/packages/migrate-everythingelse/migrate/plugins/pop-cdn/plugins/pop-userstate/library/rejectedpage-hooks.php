<?php

class PoP_CDN_UserState_RejectedPageHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:startsWith:partial',
            array($this, 'getRejectedPartialpaths')
        );
    }

    public function getRejectedPartialpaths($rejected)
    {
        
        // Exclude all the dynamic pages: those needing user state
        // All the files with a checkpoint must not be cached
        $settingsmanager = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance();
        $userstatesettingsmanager = \PoPSchema\UserState\Settings\SettingsManagerFactory::getInstance();
        foreach (\PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getRoutes() as $route) {
            // Skip if it is an internal page, we don't want to expose it
            if ($settingsmanager->isForInternalUse($route)) {
                continue;
            }

            if ($userstatesettingsmanager->requiresUserState($route)) {
                $rejected[] = $route.'/';
            }
        }

        return $rejected;
    }
}
    
/**
 * Initialize
 */
new PoP_CDN_UserState_RejectedPageHooks();

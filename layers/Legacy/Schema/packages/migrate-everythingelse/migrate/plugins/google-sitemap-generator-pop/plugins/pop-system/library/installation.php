<?php

class PoP_GSG_Installation
{
    public function __construct()
    {
        // Comment Leo 12/04/2019: commented until routes are integrated into plugin GoogleSitemapGenerator
        // (currently it deals only with pages)
        // \PoP\Root\App::addAction(
        //     'PoP:system-install', 
        //     [$this, 'systemInstall']
        // );
    }

    public function systemInstall()
    {

        // Save the pages to exclude as being those which requiresUserState
        if (GoogleSitemapGeneratorLoader::LoadPlugin()) {
            $pages = array();
            
            $settingsmanager = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance();
            foreach (\PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getRoutes() as $route) {
                if (// Remove the functional pages, such as Main, only to be accessed through a tab, eg: https://getpop.org/en/u/leo/?tab=main
                    $settingsmanager->isFunctional($route)
                    // Remove the internal pages, such as System Build/Generate/Install
                    || $settingsmanager->isForInternalUse($route)
                    // Remove the pages which need an ID to work, such as Flag as inappropriate, eg: https://getpop.org/en/flag/?pid=...
                    || $settingsmanager->needsTargetId($route)
                    // Also add all the silent pages: if they are silent, their URL does not appear on the browser, so they can't show up in Google either
                    // Notice that `silentDocument` also includes `isAppshell` pages, so no need to add them
                    || (defined('POP_ENGINEWEBPLATFORM_INITIALIZED') && $settingsmanager->silentDocument($route))
                    || (defined('POP_USERSTATE_INITIALIZED') && \PoPSchema\UserState\Settings\SettingsManagerFactory::getInstance()->requiresUserState($route))
                ) {
                    $pages[] = $route;
                }
            }

            $instance = GoogleSitemapGenerator::GetInstance();
            $instance->Initate();
            $instance->SetOption('b_exclude', $pages);
            $instance->SaveOptions();
        }
    }
}

/**
 * Initialization
 */
new PoP_GSG_Installation();

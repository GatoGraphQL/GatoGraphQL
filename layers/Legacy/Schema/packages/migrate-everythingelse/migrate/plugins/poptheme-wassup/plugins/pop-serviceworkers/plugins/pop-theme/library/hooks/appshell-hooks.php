<?php

class PoPTheme_Wassup_ServiceWorkers_Theme_Hooks_AppShell
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:appshell_pages',
            array($this, 'getAppshellPages'),
            10,
            2
        );
    }

    public function getAppshellPages($pages, $locales)
    {

        // Do not expose the un-used thememode for the appshell (eg: simple if sliding is default, and other way around)
        if (defined('POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL') && POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL) {
            $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
            $theme = $thememanager->getTheme();
            $exposed_thememodes = array(
                $theme->getDefaultThememodename(),
                GD_THEMEMODE_WASSUP_EMBED,
                GD_THEMEMODE_WASSUP_PRINT,
            );
            foreach ($theme->getThememodes() as $thememode) {
                if (!in_array($thememode->getName(), $exposed_thememodes)) {
                    foreach ($locales as $locale) {
                        unset($pages[$locale][$theme->getName()][$thememode->getName()]);
                    }
                }
            }
        }

        return $pages;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_ServiceWorkers_Theme_Hooks_AppShell();

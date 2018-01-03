<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_ServiceWorkers_Hooks_AppShell {

    function __construct() {
        
        add_filter(
            'PoP_ServiceWorkers_Job_Fetch:appshell_pages',
            array($this, 'get_appshell_pages'),
            10,
            2
        );
    }

    function get_appshell_pages($pages, $locales) {

        // Do not expose the un-used thememode for the appshell (eg: simple if sliding is default, and other way around)
        if (POP_FRONTENDENGINE_PAGE_APPSHELL) {
            
            global $gd_theme_manager;
            $theme = $gd_theme_manager->get_theme();
            $exposed_thememodes = array(
                $theme->get_default_thememodename(),
                GD_THEMEMODE_WASSUP_EMBED,
                GD_THEMEMODE_WASSUP_PRINT,
            );
            foreach ($theme->get_thememodes() as $thememode) {
                    
                if (!in_array($thememode->get_name(), $exposed_thememodes)) {

                    foreach ($locales as $locale) {

                        unset($pages[$locale][$theme->get_name()][$thememode->get_name()]);
                    }
                }
            }
        }

        return $pages;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ServiceWorkers_Hooks_AppShell();

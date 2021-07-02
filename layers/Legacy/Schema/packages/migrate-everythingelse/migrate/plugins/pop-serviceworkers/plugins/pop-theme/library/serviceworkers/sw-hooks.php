<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ServiceWorkers_Theme_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkers_Job_SW:configuration',
            array($this, 'getSwConfiguration')
        );
    }

    public function getSwConfiguration($configuration)
    {
        $configuration['${appshellPrecachedParams}']['theme'] = GD_URLPARAM_THEME;
        $configuration['${appshellPrecachedParams}']['thememode'] = GD_URLPARAM_THEMEMODE;
        $configuration['${appshellFromServerParams}'][] = GD_URLPARAM_THEMESTYLE;

        // Thememodes for the appshell
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        $theme = $thememanager->getTheme();
        $configuration['${themes}'] = array(
            'default' => $thememanager->getDefaultThemename(),
            'themes' => array(
                $theme->getName() => array(
                    'default' => $theme->getDefaultThememodename(),
                ),
            ),
        );

        return $configuration;
    }
}
    
/**
 * Initialize
 */
new PoP_ServiceWorkers_Theme_Hooks();

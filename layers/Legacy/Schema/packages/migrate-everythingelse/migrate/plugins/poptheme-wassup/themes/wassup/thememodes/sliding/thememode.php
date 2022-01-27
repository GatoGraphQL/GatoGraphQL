<?php

define('GD_THEMEMODE_WASSUP_SLIDING', 'sliding');

class GD_ThemeMode_Wassup_Sliding extends GD_WassupThemeMode_Base
{
    public function __construct()
    {
        // Hooks to allow the thememodes to do some functionality
        \PoP\Root\App::addFilter(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'backgroundLoad'));
        parent::__construct();
    }

    public function getName(): string
    {
        return GD_THEMEMODE_WASSUP_SLIDING;
    }

    public function backgroundLoad($routeConfigurations)
    {
        if (!PoPTheme_Wassup_ServerUtils::disablePreloadingPages()) {
            $routeConfigurations[POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES] = array(
                'preload' => true,
                'targets' => array(
                    \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    POP_TARGET_ADDONS,
                    POP_TARGET_MODALS,
                ),
            );
        }
        return $routeConfigurations;
    }
}

/**
 * Initialization
 */
new GD_ThemeMode_Wassup_Sliding();

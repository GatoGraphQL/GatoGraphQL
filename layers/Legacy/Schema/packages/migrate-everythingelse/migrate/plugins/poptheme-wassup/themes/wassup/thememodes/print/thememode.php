<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\App;
use PoP\Root\Constants\HookNames;

define('GD_THEMEMODE_WASSUP_PRINT', 'print');

class GD_ThemeMode_Wassup_Print extends GD_WassupThemeMode_Base
{
    public function __construct()
    {

        // App::addFilter('gd_jquery_constants', $this->jqueryConstants(...));

        // Hooks to allow the thememodes to do some functionality
        App::addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER.':'.$this->getTheme()->getName().':'.$this->getName(), $this->filteringbyShowfilter(...));
        App::addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->getTheme()->getName().':'.$this->getName(), $this->getSidebarOrientation(...));

        App::addAction(HookNames::AFTER_BOOT_APPLICATION, function() {
            if (in_array(POP_STRATUM_WEB, App::getState('strata'))) {
                App::addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->getTheme()->getName().':'.$this->getName(), $this->getPagesectionJsmethod(...), 10, 2);
                App::addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS.':'.$this->getTheme()->getName().':'.$this->getName(), '__return_false');
            }
        });

        parent::__construct();
    }

    // function jqueryConstants($jqueryConstants) {

    //     $jqueryConstants['THEMEMODE_WASSUP_PRINT'] = GD_THEMEMODE_WASSUP_PRINT;
    //     return $jqueryConstants;
    // }

    public function getName(): string
    {
        return GD_THEMEMODE_WASSUP_PRINT;
    }

    public function getPagesectionJsmethod($jsmethod, array $component)
    {

        // Remove all the scrollbars
        switch ($component[1]) {
            case self::COMPONENT_OFFCANVAS_BODY:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
                $this->removeJsmethod($jsmethod, 'scrollbarVertical');
                break;
        }

        // Add the automatic print
        switch ($component[1]) {
            case self::COMPONENT_OFFCANVAS_BODY:
                $this->addJsmethod($jsmethod, 'printWindow');
                break;
        }

        return $jsmethod;
    }

    public function filteringbyShowfilter($showfilter)
    {
        return false;
    }

    public function getSidebarOrientation($orientation)
    {
        return 'horizontal';
    }
}

/**
 * Initialization
 */
new GD_ThemeMode_Wassup_Print();

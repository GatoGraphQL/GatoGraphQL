<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

define('GD_THEMEMODE_WASSUP_PRINT', 'print');

class GD_ThemeMode_Wassup_Print extends GD_WassupThemeMode_Base
{
    public function __construct()
    {

        // HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', array($this, 'jqueryConstants'));

        // Hooks to allow the thememodes to do some functionality
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'filteringbyShowfilter'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'getSidebarOrientation'));

        HooksAPIFacade::getInstance()->addAction('popcms:boot', function() {
            $vars = ApplicationState::getVars();
            if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'getPagesectionJsmethod'), 10, 2);
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS.':'.$this->getTheme()->getName().':'.$this->getName(), '__return_false');
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

    public function getPagesectionJsmethod($jsmethod, array $module)
    {

        // Remove all the scrollbars
        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_BODY:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
                $this->removeJsmethod($jsmethod, 'scrollbarVertical');
                break;
        }

        // Add the automatic print
        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_BODY:
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

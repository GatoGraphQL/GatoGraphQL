<?php
use PoP\ComponentModel\State\ApplicationState;

define('GD_THEMEMODE_WASSUP_PRINT', 'print');

class GD_ThemeMode_Wassup_Print extends GD_WassupThemeMode_Base
{
    public function __construct()
    {

        // \PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', array($this, 'jqueryConstants'));

        // Hooks to allow the thememodes to do some functionality
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'filteringbyShowfilter'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'getSidebarOrientation'));

        \PoP\Root\App::getHookManager()->addAction('popcms:boot', function() {
            if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
                \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'getPagesectionJsmethod'), 10, 2);
                \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS.':'.$this->getTheme()->getName().':'.$this->getName(), '__return_false');
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

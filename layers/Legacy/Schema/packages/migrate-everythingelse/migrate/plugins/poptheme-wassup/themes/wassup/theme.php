<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

define('GD_THEME_WASSUP', 'wassup');

class GD_Theme_Wassup extends \PoP\Theme\Themes\ThemeBase
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter('\PoP\Theme\Themes\ThemeManagerUtils:getThemeDir:'.$this->getName(), array($this, 'themeDir'));

        // Hooks to allow the thememodes to do some functionality
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD.':'.$this->getName(), array($this, 'backgroundLoad'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER.':'.$this->getName(), array($this, 'filteringbyShowfilter'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->getName(), array($this, 'getSidebarOrientation'));

        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPMANAGERUTILS_EMBEDURL.':'.$this->getName(), array($this, 'getEmbedUrl'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPMANAGERUTILS_PRINTURL.':'.$this->getName(), array($this, 'getPrintUrl'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN.':'.$this->getName(), array($this, 'isMainScrollable'));

        // ThemeStyle
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE.':'.$this->getName(), array($this, 'getPagesectionsideLogosize'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS.':'.$this->getName(), array($this, 'getCarouselUsersGridclass'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID.':'.$this->getName(), array($this, 'getScrollinnerThumbnailGrid'));

        HooksAPIFacade::getInstance()->addAction('popcms:boot', function() {
            if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->getName(), array($this, 'getPagesectionJsmethod'), 10, 2);
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS.':'.$this->getName(), array($this, 'keepOpenTabs'));
            }
        });

        parent::__construct();
    }

    public function getName(): string
    {
        return GD_THEME_WASSUP;
    }

    public function themeDir($dir)
    {
        return dirname(__FILE__);
    }

    public function getDefaultThememodename()
    {

        // Allow to override this value. Eg: GetPoP needs the Simple theme.
        return HooksAPIFacade::getInstance()->applyFilters(
            'GD_Theme_Wassup:thememode:default',
            GD_THEMEMODE_WASSUP_SLIDING
        );
    }

    public function getDefaultThemestylename()
    {

        // Allow to override this value. Eg: GetPoP needs the Simple theme.
        return HooksAPIFacade::getInstance()->applyFilters(
            'GD_Theme_Wassup:themestyle:default',
            GD_THEMESTYLE_WASSUP_SWIFT
        );
    }

    // function addOpenTab($bool) {

    //     $filtername = sprintf(
    //         '%s:%s:%s',
    //         POP_HOOK_PAGETABS_ADDOPENTAB,
    //         $this->getName(),
    //         $this->getThemestyle()->getName()
    //     );
    //     return HooksAPIFacade::getInstance()->applyFilters($filtername, $bool);
    // }
    // function reopenTabs($bool) {

    //     $filtername = sprintf(
    //         '%s:%s:%s',
    //         POP_HOOK_SW_APPSHELL_REOPENTABS,
    //         $this->getName(),
    //         $this->getThemestyle()->getName()
    //     );
    //     return HooksAPIFacade::getInstance()->applyFilters($filtername, $bool);
    // }
    public function keepOpenTabs($bool)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $bool);
    }

    public function getScrollinnerThumbnailGrid($grid)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
            $this->getName(),
            $this->getThemestyle()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $grid);
    }

    public function getCarouselUsersGridclass($class)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_CAROUSEL_USERS_GRIDCLASS,
            $this->getName(),
            $this->getThemestyle()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $class);
    }

    public function getPagesectionsideLogosize($size)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE,
            $this->getName(),
            $this->getThemestyle()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $size);
    }

    public function backgroundLoad($routeConfigurations)
    {

        // Forward the filter to be processed by the ThemeMode
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $routeConfigurations);
    }
    public function getPagesectionJsmethod($jsmethod, array $module)
    {

        // Forward the filter to be processed by the ThemeMode
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $jsmethod, $module);
    }
    public function filteringbyShowfilter($showfilter)
    {

        // Forward the filter to be processed by the ThemeMode
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $showfilter);
    }
    public function getSidebarOrientation($orientation)
    {

        // Forward the filter to be processed by the ThemeMode
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_BLOCKSIDEBARS_ORIENTATION,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $orientation);
    }
    public function getEmbedUrl($url)
    {
        return $this->addUrlParams(
            GeneralUtils::addQueryArgs([
                GD_URLPARAM_THEMEMODE => GD_THEMEMODE_WASSUP_EMBED,
            ], $url)
        );
    }
    public function getPrintUrl($url)
    {

        // Also add param to print automatically
        return $this->addUrlParams(
            GeneralUtils::addQueryArgs([
                \PoP\ComponentModel\Constants\Params::ACTIONS.'[]' => GD_URLPARAM_ACTION_PRINT,
                GD_URLPARAM_THEMEMODE => GD_THEMEMODE_WASSUP_PRINT,
            ], $url)
        );
    }

    public function isMainScrollable($value)
    {

        // Forward the filter to be processed by the ThemeMode
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $value);
    }



    protected function addUrlParams($url)
    {
        
        // Add the themestyle, if it is not the default one
        if (!\PoP\Root\App::getState('themestyle-isdefault')) {
            $url = GeneralUtils::addQueryArgs([
                GD_URLPARAM_THEMESTYLE => \PoP\Root\App::getState('themestyle'),
            ], $url);
        }

        return $url;
    }
}

/**
 * Initialization
 */
global $gd_theme_mesym;
$gd_theme_mesym = new GD_Theme_Wassup();

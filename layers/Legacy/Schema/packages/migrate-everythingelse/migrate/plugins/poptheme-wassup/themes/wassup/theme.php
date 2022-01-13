<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;

define('GD_THEME_WASSUP', 'wassup');

class GD_Theme_Wassup extends \PoP\Theme\Themes\ThemeBase
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter('\PoP\Theme\Themes\ThemeManagerUtils:getThemeDir:'.$this->getName(), array($this, 'themeDir'));

        // Hooks to allow the thememodes to do some functionality
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD.':'.$this->getName(), array($this, 'backgroundLoad'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER.':'.$this->getName(), array($this, 'filteringbyShowfilter'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->getName(), array($this, 'getSidebarOrientation'));

        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_POPMANAGERUTILS_EMBEDURL.':'.$this->getName(), array($this, 'getEmbedUrl'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_POPMANAGERUTILS_PRINTURL.':'.$this->getName(), array($this, 'getPrintUrl'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN.':'.$this->getName(), array($this, 'isMainScrollable'));

        // ThemeStyle
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE.':'.$this->getName(), array($this, 'getPagesectionsideLogosize'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS.':'.$this->getName(), array($this, 'getCarouselUsersGridclass'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID.':'.$this->getName(), array($this, 'getScrollinnerThumbnailGrid'));

        \PoP\Root\App::getHookManager()->addAction('popcms:boot', function() {
            if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
                \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->getName(), array($this, 'getPagesectionJsmethod'), 10, 2);
                \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS.':'.$this->getName(), array($this, 'keepOpenTabs'));
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
        return \PoP\Root\App::getHookManager()->applyFilters(
            'GD_Theme_Wassup:thememode:default',
            GD_THEMEMODE_WASSUP_SLIDING
        );
    }

    public function getDefaultThemestylename()
    {

        // Allow to override this value. Eg: GetPoP needs the Simple theme.
        return \PoP\Root\App::getHookManager()->applyFilters(
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
    //     return \PoP\Root\App::getHookManager()->applyFilters($filtername, $bool);
    // }
    // function reopenTabs($bool) {

    //     $filtername = sprintf(
    //         '%s:%s:%s',
    //         POP_HOOK_SW_APPSHELL_REOPENTABS,
    //         $this->getName(),
    //         $this->getThemestyle()->getName()
    //     );
    //     return \PoP\Root\App::getHookManager()->applyFilters($filtername, $bool);
    // }
    public function keepOpenTabs($bool)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS,
            $this->getName(),
            $this->getThememode()->getName()
        );
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $bool);
    }

    public function getScrollinnerThumbnailGrid($grid)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
            $this->getName(),
            $this->getThemestyle()->getName()
        );
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $grid);
    }

    public function getCarouselUsersGridclass($class)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_CAROUSEL_USERS_GRIDCLASS,
            $this->getName(),
            $this->getThemestyle()->getName()
        );
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $class);
    }

    public function getPagesectionsideLogosize($size)
    {
        $filtername = sprintf(
            '%s:%s:%s',
            POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE,
            $this->getName(),
            $this->getThemestyle()->getName()
        );
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $size);
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
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $routeConfigurations);
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
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $jsmethod, $module);
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
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $showfilter);
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
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $orientation);
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
        return \PoP\Root\App::getHookManager()->applyFilters($filtername, $value);
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

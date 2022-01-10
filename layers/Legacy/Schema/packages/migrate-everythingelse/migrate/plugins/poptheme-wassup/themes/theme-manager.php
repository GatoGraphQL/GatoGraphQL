<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_WassupManager
{
    public function __construct()
    {

        // Catch hooks and forward them to the Themes and further on ThemeMods for their processing
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD, array($this, 'backgroundLoad'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER, array($this, 'filteringbyShowfilter'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, array($this, 'getBlocksidebarsOrientation'));

        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPMANAGERUTILS_EMBEDURL, array($this, 'getEmbedUrl'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPMANAGERUTILS_PRINTURL, array($this, 'getPrintUrl'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN, array($this, 'isMainScrollable'));

        // ThemeStyle
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE, array($this, 'getPagesectionsideLogosize'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, array($this, 'getCarouselUsersGridclass'));
        HooksAPIFacade::getInstance()->addFilter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID, array($this, 'getScrollinnerThumbnailGrid'));

        HooksAPIFacade::getInstance()->addAction('popcms:boot', function() {
            $vars = ApplicationState::getVars();
            if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, array($this, 'getPagesectionjsmethod'), 10, 2);
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, array($this, 'getBlockjsmethod'), 10, 2);
                HooksAPIFacade::getInstance()->addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS, array($this, 'keepOpenTabs'));
            }
        });
    }

    public function keepOpenTabs($bool)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $bool;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $bool);
    }

    public function getScrollinnerThumbnailGrid($grid)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $grid;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $grid);
    }
    public function getCarouselUsersGridclass($class)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $class;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_CAROUSEL_USERS_GRIDCLASS,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $class);
    }
    public function getPagesectionsideLogosize($size)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $size;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $size);
    }
    public function backgroundLoad($routeConfigurations)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $routeConfigurations;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $routeConfigurations);
    }
    public function getPagesectionjsmethod($jsmethod, array $module)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $jsmethod;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $jsmethod, $module);
    }
    public function getBlockjsmethod($jsmethod, array $module)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $jsmethod;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $jsmethod, $module);
    }
    public function filteringbyShowfilter($showfilter)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $showfilter;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $showfilter);
    }
    public function getBlocksidebarsOrientation($orientation)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $orientation;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_BLOCKSIDEBARS_ORIENTATION,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $orientation);
    }

    public function getEmbedUrl($url)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $url;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_POPMANAGERUTILS_EMBEDURL,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $url);
    }
    public function getPrintUrl($url)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $url;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_POPMANAGERUTILS_PRINTURL,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $url);
    }
    public function isMainScrollable($value)
    {
        $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
        if (!($theme = $thememanager->getTheme())) {
            return $value;
        }

        $filtername = sprintf(
            '%s:%s',
            POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN,
            $theme->getName()
        );
        return HooksAPIFacade::getInstance()->applyFilters($filtername, $value);
    }
}

/**
 * Initialization
 */
new PoPTheme_WassupManager();

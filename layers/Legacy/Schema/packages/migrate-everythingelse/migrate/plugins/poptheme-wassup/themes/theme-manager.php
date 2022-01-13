<?php
use PoP\ComponentModel\State\ApplicationState;

class PoPTheme_WassupManager
{
    public function __construct()
    {

        // Catch hooks and forward them to the Themes and further on ThemeMods for their processing
        \PoP\Root\App::addFilter(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD, array($this, 'backgroundLoad'));
        \PoP\Root\App::addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER, array($this, 'filteringbyShowfilter'));
        \PoP\Root\App::addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, array($this, 'getBlocksidebarsOrientation'));

        \PoP\Root\App::addFilter(POP_HOOK_POPMANAGERUTILS_EMBEDURL, array($this, 'getEmbedUrl'));
        \PoP\Root\App::addFilter(POP_HOOK_POPMANAGERUTILS_PRINTURL, array($this, 'getPrintUrl'));
        \PoP\Root\App::addFilter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN, array($this, 'isMainScrollable'));

        // ThemeStyle
        \PoP\Root\App::addFilter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE, array($this, 'getPagesectionsideLogosize'));
        \PoP\Root\App::addFilter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, array($this, 'getCarouselUsersGridclass'));
        \PoP\Root\App::addFilter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID, array($this, 'getScrollinnerThumbnailGrid'));

        \PoP\Root\App::addAction('popcms:boot', function() {
            if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
                \PoP\Root\App::addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, array($this, 'getPagesectionjsmethod'), 10, 2);
                \PoP\Root\App::addFilter(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, array($this, 'getBlockjsmethod'), 10, 2);
                \PoP\Root\App::addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS, array($this, 'keepOpenTabs'));
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
        return \PoP\Root\App::applyFilters($filtername, $bool);
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
        return \PoP\Root\App::applyFilters($filtername, $grid);
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
        return \PoP\Root\App::applyFilters($filtername, $class);
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
        return \PoP\Root\App::applyFilters($filtername, $size);
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
        return \PoP\Root\App::applyFilters($filtername, $routeConfigurations);
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
        return \PoP\Root\App::applyFilters($filtername, $jsmethod, $module);
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
        return \PoP\Root\App::applyFilters($filtername, $jsmethod, $module);
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
        return \PoP\Root\App::applyFilters($filtername, $showfilter);
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
        return \PoP\Root\App::applyFilters($filtername, $orientation);
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
        return \PoP\Root\App::applyFilters($filtername, $url);
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
        return \PoP\Root\App::applyFilters($filtername, $url);
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
        return \PoP\Root\App::applyFilters($filtername, $value);
    }
}

/**
 * Initialization
 */
new PoPTheme_WassupManager();

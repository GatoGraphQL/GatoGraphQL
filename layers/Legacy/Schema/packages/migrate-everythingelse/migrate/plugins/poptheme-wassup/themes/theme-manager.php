<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\App;
use PoP\Root\Constants\HookNames;

class PoPTheme_WassupManager
{
    public function __construct()
    {

        // Catch hooks and forward them to the Themes and further on ThemeMods for their processing
        App::addFilter(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD, $this->backgroundLoad(...));
        App::addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER, $this->filteringbyShowfilter(...));
        App::addFilter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, $this->getBlocksidebarsOrientation(...));

        App::addFilter(POP_HOOK_POPMANAGERUTILS_EMBEDURL, $this->getEmbedUrl(...));
        App::addFilter(POP_HOOK_POPMANAGERUTILS_PRINTURL, $this->getPrintUrl(...));
        App::addFilter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN, $this->isMainScrollable(...));

        // ThemeStyle
        App::addFilter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE, $this->getPagesectionsideLogosize(...));
        App::addFilter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, $this->getCarouselUsersGridclass(...));
        App::addFilter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID, $this->getScrollinnerThumbnailGrid(...));

        App::addAction(HookNames::AFTER_BOOT_APPLICATION, function() {
            if (in_array(POP_STRATUM_WEB, App::getState('strata'))) {
                App::addFilter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, $this->getPagesectionjsmethod(...), 10, 2);
                App::addFilter(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, $this->getBlockjsmethod(...), 10, 2);
                App::addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS, $this->keepOpenTabs(...));
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
        return App::applyFilters($filtername, $bool);
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
        return App::applyFilters($filtername, $grid);
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
        return App::applyFilters($filtername, $class);
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
        return App::applyFilters($filtername, $size);
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
        return App::applyFilters($filtername, $routeConfigurations);
    }
    public function getPagesectionjsmethod($jsmethod, array $componentVariation)
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
        return App::applyFilters($filtername, $jsmethod, $componentVariation);
    }
    public function getBlockjsmethod($jsmethod, array $componentVariation)
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
        return App::applyFilters($filtername, $jsmethod, $componentVariation);
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
        return App::applyFilters($filtername, $showfilter);
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
        return App::applyFilters($filtername, $orientation);
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
        return App::applyFilters($filtername, $url);
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
        return App::applyFilters($filtername, $url);
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
        return App::applyFilters($filtername, $value);
    }
}

/**
 * Initialization
 */
new PoPTheme_WassupManager();

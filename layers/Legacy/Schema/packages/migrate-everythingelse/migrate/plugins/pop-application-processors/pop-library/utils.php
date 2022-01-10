<?php
use PoP\Application\Constants\Actions;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

define('POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN', 'wassuputils-scrollablemain');

class PoP_ApplicationProcessors_Utils
{
    public static function getInitializedomainModules()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_ApplicationProcessors_Utils:initializedomain:modules',
            array()
        );
    }

    public static function addAppliesto()
    {

        // Add the "Applies To" box if the filter adding all the values has been defined
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addAppliesto', false) && has_filter('wassup_appliesto');
    }

    public static function addSections()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addSections', true);
    }

    public static function addCategories()
    {

        // By default, do not add the categories
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addCategories', false) && has_filter('wassup_categories');
    }

    public static function addAuthorWidgetDetails()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addAuthorWidgetDetails', false);
    }

    public static function addCategoriesToWidget()
    {
        // If not using categories in general, then of course no need to add them to the widget
        return self::addCategories() && HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addCategoriesToWidget', false);
    }

    public static function addLinkAccesstype()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addLinkAccesstype', false);
    }

    protected static function loadingLazy()
    {
        $vars = ApplicationState::getVars();
        return in_array(Actions::LOADLAZY, \PoP\Root\App::getState('actions'));
    }

    public static function feedSimpleviewLazyload()
    {
        if (self::loadingLazy()) {
            return false;
        }
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:feedSimpleviewLazyload', true);
    }

    public static function feedDetailsLazyload()
    {
        if (self::loadingLazy()) {
            return false;
        }
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:feedDetailsLazyload', false);
    }

    public static function authorFulldescription()
    {

        // Show the author profile's description in the body? or as a widget?
        // Default: as a widget
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:authorFulldescription', false);
    }

    public static function addBackgroundMenu()
    {

        // Allow the background to not have the menu. Needed for GetPoP
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addBackgroundMenu', false);
    }

    public static function getWelcomeTitle($add_tagline = false)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $welcometitle = sprintf(
            TranslationAPIFacade::getInstance()->__('Welcome to %s!', 'poptheme-wassup'),
            $cmsapplicationapi->getSiteName()
        );
        if ($add_tagline) {
            $welcometitle = sprintf(
                '<span class="welcometitle">%s</span><br/><small class="tagline">%s</small>',
                $welcometitle,
                $cmsapplicationapi->getSiteDescription()
            );
        }

        // Allow Organik Fundraising to override the welcome title
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:welcome_title', $welcometitle);
    }

    public static function addMainpagesectionScrollbar()
    {

        // Comment Leo 14/03/2017: The embed must be scrollable, because the fullscreen scrollbar doesn't work! Otherwise, it can't allow fullscreen mode
        // $vars = ApplicationState::getVars();
        // if (\PoP\Root\App::getState('theme') == GD_THEME_WASSUP && \PoP\Root\App::getState('thememode') == GD_THEMEMODE_WASSUP_EMBED) {

        //     return true;
        // }
        return HooksAPIFacade::getInstance()->applyFilters(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN, false);
        // return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:addMainpagesectionScrollbar', false);
    }

    // public static function narrowBody() {

    //     return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:narrowBody', true);
    // }
    public static function narrowBodyHome()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_ApplicationProcessors_Utils:narrowBodyHome', true);
    }
}

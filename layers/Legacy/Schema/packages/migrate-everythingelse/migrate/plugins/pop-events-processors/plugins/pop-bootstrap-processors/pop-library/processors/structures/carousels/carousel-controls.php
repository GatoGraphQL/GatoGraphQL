<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class GD_EM_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public const MODULE_CAROUSELCONTROLS_EVENTS = 'carouselcontrols-events';
    public const MODULE_CAROUSELCONTROLS_AUTHOREVENTS = 'carouselcontrols-authorevents';
    public const MODULE_CAROUSELCONTROLS_TAGEVENTS = 'carouselcontrols-tagevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_EVENTS],
            [self::class, self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS],
            [self::class, self::MODULE_CAROUSELCONTROLS_TAGEVENTS],
        );
    }

    public function getControlClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($module);
    }

    public function getTitleClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($module);
    }
    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                return getRouteIcon(POP_EVENTS_ROUTE_EVENTS, true).sprintf(
                    '<span class="hidden-sm hidden-md hidden-lg">%s</span><span class="hidden-xs">%s</span>',
                    TranslationAPIFacade::getInstance()->__('Events', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('Upcoming Events', 'poptheme-wassup')
                );
        }

        return parent::getTitle($module, $props);
    }
    protected function getTitleLink(array $module, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
                return RouteUtils::getRouteURL(POP_EVENTS_ROUTE_EVENTS);

            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS => POP_EVENTS_ROUTE_EVENTS,
                );
                return RequestUtils::addRoute($url, $routes[$module[1]] ?? null);

            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_TAGEVENTS => POP_EVENTS_ROUTE_EVENTS,
                );
                return RequestUtils::addRoute($url, $routes[$module[1]] ?? null);
        }

        return parent::getTitleLink($module, $props);
    }
}



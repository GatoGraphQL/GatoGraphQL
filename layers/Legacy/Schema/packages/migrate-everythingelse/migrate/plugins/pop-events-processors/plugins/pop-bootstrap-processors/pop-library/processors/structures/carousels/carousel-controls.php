<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class GD_EM_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_EVENTS = 'carouselcontrols-events';
    public final const MODULE_CAROUSELCONTROLS_AUTHOREVENTS = 'carouselcontrols-authorevents';
    public final const MODULE_CAROUSELCONTROLS_TAGEVENTS = 'carouselcontrols-tagevents';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_EVENTS],
            [self::class, self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS],
            [self::class, self::MODULE_CAROUSELCONTROLS_TAGEVENTS],
        );
    }

    public function getControlClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($componentVariation);
    }

    public function getTitleClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($componentVariation);
    }
    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                return getRouteIcon(POP_EVENTS_ROUTE_EVENTS, true).sprintf(
                    '<span class="hidden-sm hidden-md hidden-lg">%s</span><span class="hidden-xs">%s</span>',
                    TranslationAPIFacade::getInstance()->__('Events', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('Upcoming Events', 'poptheme-wassup')
                );
        }

        return parent::getTitle($componentVariation, $props);
    }
    protected function getTitleLink(array $componentVariation, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_EVENTS:
                return RouteUtils::getRouteURL(POP_EVENTS_ROUTE_EVENTS);

            case self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_AUTHOREVENTS => POP_EVENTS_ROUTE_EVENTS,
                );
                return RequestUtils::addRoute($url, $routes[$componentVariation[1]] ?? null);

            case self::MODULE_CAROUSELCONTROLS_TAGEVENTS:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_TAGEVENTS => POP_EVENTS_ROUTE_EVENTS,
                );
                return RequestUtils::addRoute($url, $routes[$componentVariation[1]] ?? null);
        }

        return parent::getTitleLink($componentVariation, $props);
    }
}



<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class GD_EM_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const COMPONENT_CAROUSELCONTROLS_EVENTS = 'carouselcontrols-events';
    public final const COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS = 'carouselcontrols-authorevents';
    public final const COMPONENT_CAROUSELCONTROLS_TAGEVENTS = 'carouselcontrols-tagevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELCONTROLS_EVENTS],
            [self::class, self::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS],
            [self::class, self::COMPONENT_CAROUSELCONTROLS_TAGEVENTS],
        );
    }

    public function getControlClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_EVENTS:
            case self::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::COMPONENT_CAROUSELCONTROLS_TAGEVENTS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($component);
    }

    public function getTitleClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_EVENTS:
            case self::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::COMPONENT_CAROUSELCONTROLS_TAGEVENTS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($component);
    }
    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_EVENTS:
            case self::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS:
            case self::COMPONENT_CAROUSELCONTROLS_TAGEVENTS:
                return getRouteIcon(POP_EVENTS_ROUTE_EVENTS, true).sprintf(
                    '<span class="hidden-sm hidden-md hidden-lg">%s</span><span class="hidden-xs">%s</span>',
                    TranslationAPIFacade::getInstance()->__('Events', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('Upcoming Events', 'poptheme-wassup')
                );
        }

        return parent::getTitle($component, $props);
    }
    protected function getTitleLink(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_EVENTS:
                return RouteUtils::getRouteURL(POP_EVENTS_ROUTE_EVENTS);

            case self::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS => POP_EVENTS_ROUTE_EVENTS,
                );
                return RequestUtils::addRoute($url, $routes[$component[1]] ?? null);

            case self::COMPONENT_CAROUSELCONTROLS_TAGEVENTS:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                $routes = array(
                    self::COMPONENT_CAROUSELCONTROLS_TAGEVENTS => POP_EVENTS_ROUTE_EVENTS,
                );
                return RequestUtils::addRoute($url, $routes[$component[1]] ?? null);
        }

        return parent::getTitleLink($component, $props);
    }
}



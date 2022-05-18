<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Events_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_CUSTOMANCHORCONTROL_CALENDAR = 'custombuttoncontrol-calendar';
    public final const COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS = 'custombuttoncontrol-pastevents';
    public final const COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS = 'custombuttoncontrol-authorpastevents';
    public final const COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS = 'custombuttoncontrol-tagpastevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CUSTOMANCHORCONTROL_CALENDAR],
            [self::class, self::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS],
            [self::class, self::COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS],
            [self::class, self::COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_CALENDAR:
                return TranslationAPIFacade::getInstance()->__('Calendar', 'poptheme-wassup');

            case self::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('Past Events', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_CALENDAR:
                return getRouteIcon(POP_EVENTS_ROUTE_EVENTSCALENDAR, false);

            case self::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                return getRouteIcon(POP_EVENTS_ROUTE_PASTEVENTS, false);
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_CALENDAR:
            case self::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS:
                $routes = array(
                    self::COMPONENT_CUSTOMANCHORCONTROL_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
                    self::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS => POP_EVENTS_ROUTE_PASTEVENTS,
                );
                $route = $routes[$component[1]];

                return RouteUtils::getRouteURL($route);

            case self::COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                // Allow URE to add the ContentSource
                return \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomAnchorControls:pastevents:url',
                    RequestUtils::addRoute($url, POP_EVENTS_ROUTE_PASTEVENTS),
                    $author
                );

            case self::COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                return RequestUtils::addRoute($url, POP_EVENTS_ROUTE_PASTEVENTS);
        }

        return parent::getHref($component, $props);
    }


    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_CALENDAR:
            case self::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                $this->appendProp($component, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



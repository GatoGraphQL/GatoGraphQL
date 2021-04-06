<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\RequestUtils;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class PoP_Events_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_CUSTOMANCHORCONTROL_CALENDAR = 'custombuttoncontrol-calendar';
    public const MODULE_CUSTOMANCHORCONTROL_PASTEVENTS = 'custombuttoncontrol-pastevents';
    public const MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS = 'custombuttoncontrol-authorpastevents';
    public const MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS = 'custombuttoncontrol-tagpastevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_CALENDAR],
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS],
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS],
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_CALENDAR:
                return TranslationAPIFacade::getInstance()->__('Calendar', 'poptheme-wassup');

            case self::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('Past Events', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_CALENDAR:
                return getRouteIcon(POP_EVENTS_ROUTE_EVENTSCALENDAR, false);

            case self::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                return getRouteIcon(POP_EVENTS_ROUTE_PASTEVENTS, false);
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_CALENDAR:
            case self::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS:
                $routes = array(
                    self::MODULE_CUSTOMANCHORCONTROL_CALENDAR => POP_EVENTS_ROUTE_EVENTSCALENDAR,
                    self::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS => POP_EVENTS_ROUTE_PASTEVENTS,
                );
                $route = $routes[$module[1]];

                return RouteUtils::getRouteURL($route);

            case self::MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
                $author = $vars['routing-state']['queried-object-id'];
                $url = $cmsusersapi->getUserURL($author);
                // Allow URE to add the ContentSource
                return HooksAPIFacade::getInstance()->applyFilters(
                    'GD_EM_Module_Processor_CustomAnchorControls:pastevents:url',
                    RequestUtils::addRoute($url, POP_EVENTS_ROUTE_PASTEVENTS),
                    $author
                );

            case self::MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                $url = $postTagTypeAPI->getTagURL($vars['routing-state']['queried-object-id']);
                return RequestUtils::addRoute($url, POP_EVENTS_ROUTE_PASTEVENTS);
        }

        return parent::getHref($module, $props);
    }


    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_CALENDAR:
            case self::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS:
                $this->appendProp($module, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



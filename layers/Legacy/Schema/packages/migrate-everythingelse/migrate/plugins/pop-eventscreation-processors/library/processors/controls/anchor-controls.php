<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventsCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS = 'custombuttoncontrol-mypastevents';
    public final const COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT = 'custombuttoncontrol-addevent';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS,
            self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('My Past Events', 'poptheme-wassup');

            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT:
                return TranslationAPIFacade::getInstance()->__('Add Event', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                return getRouteIcon(POP_EVENTSCREATION_ROUTE_MYPASTEVENTS, false);

            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT:
                return 'fa-plus';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS:
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT:
                $routes = array(
                    self::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
                    self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT => POP_EVENTSCREATION_ROUTE_ADDEVENT,
                );
                $route = $routes[$component->name];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                $this->appendProp($component, $props, 'class', 'btn btn-link btn-compact');
                break;

            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT:
                $this->appendProp($component, $props, 'class', 'btn btn-primary');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



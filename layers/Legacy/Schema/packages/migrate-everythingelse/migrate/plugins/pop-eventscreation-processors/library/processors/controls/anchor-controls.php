<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventsCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS = 'custombuttoncontrol-mypastevents';
    public final const MODULE_CUSTOMANCHORCONTROL_ADDEVENT = 'custombuttoncontrol-addevent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS],
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('My Past Events', 'poptheme-wassup');

            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                return TranslationAPIFacade::getInstance()->__('Add Event', 'poptheme-wassup');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                return getRouteIcon(POP_EVENTSCREATION_ROUTE_MYPASTEVENTS, false);

            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                return 'fa-plus';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                $routes = array(
                    self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
                    self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT => POP_EVENTSCREATION_ROUTE_ADDEVENT,
                );
                $route = $routes[$componentVariation[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($componentVariation, $props);
    }
    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-link btn-compact');
                break;

            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-primary');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



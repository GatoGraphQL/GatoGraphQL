<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventsCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS = 'custombuttoncontrol-mypastevents';
    public const MODULE_CUSTOMANCHORCONTROL_ADDEVENT = 'custombuttoncontrol-addevent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS],
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('My Past Events', 'poptheme-wassup');

            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                return TranslationAPIFacade::getInstance()->__('Add Event', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                return getRouteIcon(POP_EVENTSCREATION_ROUTE_MYPASTEVENTS, false);

            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                return 'fa-plus';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                $routes = array(
                    self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
                    self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT => POP_EVENTSCREATION_ROUTE_ADDEVENT,
                );
                $route = $routes[$module[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS:
                $this->appendProp($module, $props, 'class', 'btn btn-link btn-compact');
                break;

            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENT:
                $this->appendProp($module, $props, 'class', 'btn btn-primary');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



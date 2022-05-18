<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK = 'custombuttoncontrol-addeventlink';

    public function getComponentsToProcess(): array
    {
        return array(
            [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return RouteUtils::getRouteURL(POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK);
        }

        return parent::getHref($component, $props);
    }

    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                $this->appendProp($component, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK = 'custombuttoncontrol-addeventlink';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return RouteUtils::getRouteURL(POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK);
        }

        return parent::getHref($component, $props);
    }

    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
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
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                $this->appendProp($component, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_LocationPostLinksCreation_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK = 'custombuttoncontrol-addlocationpostlink';

    public function getComponentsToProcess(): array
    {
        return array(
            [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'pop-locationpostlinkscreation-processors');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return RouteUtils::getRouteURL(POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK);
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
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
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                $this->appendProp($component, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



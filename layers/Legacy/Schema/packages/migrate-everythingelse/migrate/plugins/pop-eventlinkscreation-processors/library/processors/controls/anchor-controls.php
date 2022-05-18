<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK = 'custombuttoncontrol-addeventlink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return RouteUtils::getRouteURL(POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK);
        }

        return parent::getHref($componentVariation, $props);
    }

    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
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
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_LocationPostLinksCreation_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK = 'custombuttoncontrol-addlocationpostlink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'pop-locationpostlinkscreation-processors');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return RouteUtils::getRouteURL(POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK);
        }

        return parent::getHref($componentVariation, $props);
    }
    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
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
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



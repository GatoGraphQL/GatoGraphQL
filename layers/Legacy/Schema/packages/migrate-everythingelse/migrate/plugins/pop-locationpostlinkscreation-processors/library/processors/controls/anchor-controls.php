<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_LocationPostLinksCreation_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK = 'custombuttoncontrol-addlocationpostlink';

    public function getModulesToProcess(): array
    {
        return array(
            [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'pop-locationpostlinkscreation-processors');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                return RouteUtils::getRouteURL(POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK);
        }

        return parent::getHref($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
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
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
                $this->appendProp($module, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



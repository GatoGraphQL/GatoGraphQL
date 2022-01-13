<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK = 'custombuttoncontrol-addeventlink';

    public function getModulesToProcess(): array
    {
        return array(
            [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                return RouteUtils::getRouteURL(POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK);
        }

        return parent::getHref($module, $props);
    }

    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
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
            case self::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK:
                $this->appendProp($module, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



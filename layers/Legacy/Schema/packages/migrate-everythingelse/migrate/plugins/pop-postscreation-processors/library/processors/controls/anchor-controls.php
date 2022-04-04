<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_ADDPOSTLINK = 'buttoncontrol-addpostlink';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_ADDPOSTLINK],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                $routes = array(
                    self::MODULE_ANCHORCONTROL_ADDPOSTLINK => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
                );
                $route = $routes[$module[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
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
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                $this->appendProp($module, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



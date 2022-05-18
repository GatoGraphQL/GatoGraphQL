<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_ADDPOSTLINK = 'buttoncontrol-addpostlink';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_ADDPOSTLINK],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                $routes = array(
                    self::MODULE_ANCHORCONTROL_ADDPOSTLINK => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
                );
                $route = $routes[$component[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
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
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                $this->appendProp($component, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



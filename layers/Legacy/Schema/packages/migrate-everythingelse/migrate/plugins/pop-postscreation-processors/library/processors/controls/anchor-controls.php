<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_ADDPOSTLINK = 'buttoncontrol-addpostlink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_ADDPOSTLINK],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                return TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                return 'fa-link';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                $routes = array(
                    self::MODULE_ANCHORCONTROL_ADDPOSTLINK => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
                );
                $route = $routes[$componentVariation[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($componentVariation, $props);
    }
    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
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
            case self::MODULE_ANCHORCONTROL_ADDPOSTLINK:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-info aslink');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



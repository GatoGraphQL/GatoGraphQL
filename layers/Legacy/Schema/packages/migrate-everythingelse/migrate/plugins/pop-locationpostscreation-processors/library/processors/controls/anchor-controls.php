<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class CommonPagesEM_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST = 'custombuttoncontrol-addlocationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('Add %s', 'pop-locationpostscreation-processors'),
                    PoP_LocationPosts_PostNameUtils::getNameUc()
                );
            break;
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                return 'fa-plus';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST) {
                    $routes = array(
                        self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
                    );
                    $route = $routes[$component[1]];

                    return RouteUtils::getRouteURL($route);
                }
                break;
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($component, $props);
    }
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                $this->appendProp($component, $props, 'class', 'btn btn-primary');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



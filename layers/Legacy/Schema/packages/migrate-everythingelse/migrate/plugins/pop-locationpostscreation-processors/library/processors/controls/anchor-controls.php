<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class CommonPagesEM_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST = 'custombuttoncontrol-addlocationpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('Add %s', 'pop-locationpostscreation-processors'),
                    PoP_LocationPosts_PostNameUtils::getNameUc()
                );
            break;
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                return 'fa-plus';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST) {
                    $routes = array(
                        self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
                    );
                    $route = $routes[$componentVariation[1]];

                    return RouteUtils::getRouteURL($route);
                }
                break;
        }

        return parent::getHref($componentVariation, $props);
    }
    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
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
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-primary');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



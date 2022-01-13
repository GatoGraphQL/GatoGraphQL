<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class CommonPagesEM_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST = 'custombuttoncontrol-addlocationpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('Add %s', 'pop-locationpostscreation-processors'),
                    PoP_LocationPosts_PostNameUtils::getNameUc()
                );
            break;
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                return 'fa-plus';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST) {
                    $routes = array(
                        self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
                    );
                    $route = $routes[$module[1]];

                    return RouteUtils::getRouteURL($route);
                }
                break;
        }

        return parent::getHref($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
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
            case self::MODULE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
                $this->appendProp($module, $props, 'class', 'btn btn-primary');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



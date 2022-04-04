<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_Module_Processor_TypeaheadAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_CREATELOCATION = 'anchorcontrol-createlocation';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_CREATELOCATION],
        );
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                return 'fa-plus';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                return RouteUtils::getRouteURL(POP_ADDLOCATIONS_ROUTE_ADDLOCATION);
        }

        return parent::getHref($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                $this->appendProp($module, $props, 'class', 'btn btn-primary pop-createlocation-btn');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



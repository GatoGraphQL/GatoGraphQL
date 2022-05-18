<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_Module_Processor_TypeaheadAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_CREATELOCATION = 'anchorcontrol-createlocation';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_CREATELOCATION],
        );
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                return 'fa-plus';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getTarget(array $componentVariation, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                return RouteUtils::getRouteURL(POP_ADDLOCATIONS_ROUTE_ADDLOCATION);
        }

        return parent::getHref($componentVariation, $props);
    }
    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_CREATELOCATION:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-primary pop-createlocation-btn');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



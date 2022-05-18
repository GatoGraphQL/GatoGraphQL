<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_Module_Processor_TypeaheadAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_CREATELOCATION = 'anchorcontrol-createlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ANCHORCONTROL_CREATELOCATION],
        );
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_CREATELOCATION:
                return 'fa-plus';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getTarget(array $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_CREATELOCATION:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_CREATELOCATION:
                return RouteUtils::getRouteURL(POP_ADDLOCATIONS_ROUTE_ADDLOCATION);
        }

        return parent::getHref($component, $props);
    }
    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_CREATELOCATION:
                $this->appendProp($component, $props, 'class', 'btn btn-primary pop-createlocation-btn');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



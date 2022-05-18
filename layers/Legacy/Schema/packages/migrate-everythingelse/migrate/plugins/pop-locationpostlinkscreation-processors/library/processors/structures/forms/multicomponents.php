<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_LocationPostLinksCreation_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE = 'multicomponent-form-locationpostlink-rightside';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $status = GD_CreateUpdate_Utils::moderate() ?
            [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
            [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE:
                $details = array(
                    self::COMPONENT_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE => [PoP_LocationPostLinks_Module_Processor_FormWidgets::class, PoP_LocationPostLinks_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS],
                );
                $ret[] = $details[$component[1]];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_METAINFORMATION];
                $ret[] = $status;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE:
                if (!($classs = $this->getProp($component, $props, 'forminput-publish-class')/*$this->get_general_prop($props, 'forminput-publish-class')*/)) {
                    $classs = 'alert alert-info';
                }
                $status = GD_CreateUpdate_Utils::moderate() ?
                    [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
                    [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $this->appendProp($status, $props, 'class', $classs);
                break;
        }

        parent::initModelProps($component, $props);
    }
}




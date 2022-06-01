<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class GD_EM_Custom_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE = 'multicomponent-form-event-rightside';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $status = GD_CreateUpdate_Utils::moderate() ?
            [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
            [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE:
                $details = array(
                    self::COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE => [GD_EM_Custom_Module_Processor_FormWidgets::class, GD_EM_Custom_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_EVENTDETAILS],
                );
                $ret[] = $details[$component->name];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_METAINFORMATION];
                $ret[] = $status;
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE:
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




<?php

abstract class GD_EM_Module_Processor_LocationMapConditionWrappersBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getLocationlinksTemplate(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getMapSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::COMPONENT_MAP_SIDEBARINDIVIDUAL];
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        if ($locationslinks = $this->getLocationlinksTemplate($component)) {
            $ret[] = $locationslinks;
        }
        if ($map = $this->getMapSubcomponent($component)) {
            $ret[] = $map;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return 'hasLocation';
    }

    public function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        $ret[] = [GD_EM_Module_Processor_WidgetMessages::class, GD_EM_Module_Processor_WidgetMessages::COMPONENT_EM_MESSAGE_NOLOCATION];

        return $ret;
    }
}

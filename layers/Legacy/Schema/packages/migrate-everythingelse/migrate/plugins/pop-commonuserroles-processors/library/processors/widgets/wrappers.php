<?php

class GD_URE_Custom_Module_Processor_SidebarComponentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS = 'ure-layoutwrapper-profileindividual-details';
    public final const MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS = 'ure-layoutwrapper-profileorganization-details';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS],
            [self::class, self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_ProfileIndividualLayouts::class, GD_URE_Custom_Module_Processor_ProfileIndividualLayouts::COMPONENT_URE_LAYOUT_PROFILEINDIVIDUAL_DETAILS];
                break;

            case self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_ProfileOrganizationLayouts::class, GD_URE_Custom_Module_Processor_ProfileOrganizationLayouts::COMPONENT_URE_LAYOUT_PROFILEORGANIZATION_DETAILS];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $component)
    {
        $ret = parent::getConditionFailedSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
            case self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_WidgetMessages::class, GD_URE_Custom_Module_Processor_WidgetMessages::COMPONENT_URE_MESSAGE_NODETAILS];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
                return 'hasIndividualDetails';

            case self::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:
                return 'hasOrganizationDetails';
        }

        return 'hasOrganizationDetails';
    }
}




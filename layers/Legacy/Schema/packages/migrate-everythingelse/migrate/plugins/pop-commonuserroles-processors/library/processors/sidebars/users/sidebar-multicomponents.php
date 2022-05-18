<?php

class GD_URE_Custom_Module_Processor_UserMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_ORGANIZATION = 'sidebarmulticomponent-organization';
    public final const MODULE_SIDEBARMULTICOMPONENT_INDIVIDUAL = 'sidebarmulticomponent-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_ORGANIZATION],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_INDIVIDUAL],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_ORGANIZATION:
                $ret[] = [GD_URE_Custom_Module_Processor_UserWidgets::class, GD_URE_Custom_Module_Processor_UserWidgets::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO];
                $ret[] = [GD_URE_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Module_Processor_SidebarComponentsWrappers::MODULE_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES];
                
                // Show the Author Description inside the widget instead of the body?
                if (!PoP_ApplicationProcessors_Utils::authorFulldescription()) {
                    $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION];
                }
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_INDIVIDUAL:
                $ret[] = [GD_URE_Custom_Module_Processor_UserWidgets::class, GD_URE_Custom_Module_Processor_UserWidgets::MODULE_WIDGETCOMPACT_INDIVIDUALINFO];
                $ret[] = [GD_URE_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Module_Processor_SidebarComponentsWrappers::MODULE_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES];
                
                // Show the Author Description inside the widget instead of the body?
                if (!PoP_ApplicationProcessors_Utils::authorFulldescription()) {
                    $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION];
                }
                break;
        }

        return $ret;
    }
}




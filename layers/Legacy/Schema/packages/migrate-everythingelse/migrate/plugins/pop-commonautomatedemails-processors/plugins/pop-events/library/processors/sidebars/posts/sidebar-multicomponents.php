<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT = 'sidebarmulticomponent-automatedemails-event';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT:
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_EM_AE_Module_Processor_Widgets::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_AE_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS];
                break;
            break;
        }

        return $ret;
    }
}




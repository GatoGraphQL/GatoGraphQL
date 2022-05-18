<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT = 'sidebarmulticomponent-automatedemails-event';
    
    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT:
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_EM_AE_Module_Processor_Widgets::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_AE_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS];
                break;
            break;
        }

        return $ret;
    }
}




<?php

class UserStance_Module_Processor_CustomPostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_STANCELEFT = 'sidebarmulticomponent-stanceleft';
    public final const MODULE_SIDEBARMULTICOMPONENT_STANCERIGHT = 'sidebarmulticomponent-stanceright';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_STANCELEFT],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_STANCERIGHT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_STANCELEFT:
                $ret[] = [UserStance_Module_Processor_CustomPostWidgets::class, UserStance_Module_Processor_CustomPostWidgets::MODULE_WIDGETCOMPACT_STANCEINFO];
                $ret[] = [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_WIDGETWRAPPER_STANCETARGET];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                break;
                
            case self::MODULE_SIDEBARMULTICOMPONENT_STANCERIGHT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}




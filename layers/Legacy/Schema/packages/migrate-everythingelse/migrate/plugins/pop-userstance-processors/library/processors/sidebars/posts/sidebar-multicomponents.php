<?php

class UserStance_Module_Processor_CustomPostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_SIDEBARMULTICOMPONENT_STANCELEFT = 'sidebarmulticomponent-stanceleft';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_STANCERIGHT = 'sidebarmulticomponent-stanceright';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_STANCELEFT],
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_STANCERIGHT],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_SIDEBARMULTICOMPONENT_STANCELEFT:
                $ret[] = [UserStance_Module_Processor_CustomPostWidgets::class, UserStance_Module_Processor_CustomPostWidgets::COMPONENT_WIDGETCOMPACT_STANCEINFO];
                $ret[] = [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::COMPONENT_WIDGETWRAPPER_STANCETARGET];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                break;
                
            case self::COMPONENT_SIDEBARMULTICOMPONENT_STANCERIGHT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}




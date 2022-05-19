<?php

class PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE = 'quicklinkbuttongroup-usersendmessage';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE:
                $ret[] = [PoP_SocialNetwork_Module_Processor_UserViewComponentButtons::class, PoP_SocialNetwork_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW];
                break;
        }
        
        return $ret;
    }
}



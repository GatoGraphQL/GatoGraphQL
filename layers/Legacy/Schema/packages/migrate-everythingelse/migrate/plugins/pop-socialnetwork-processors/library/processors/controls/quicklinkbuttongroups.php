<?php

class PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE = 'quicklinkbuttongroup-usersendmessage';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE:
                $ret[] = [PoP_SocialNetwork_Module_Processor_UserViewComponentButtons::class, PoP_SocialNetwork_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW];
                break;
        }
        
        return $ret;
    }
}


